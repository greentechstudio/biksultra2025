<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\XenditService;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class XenditWebhookController extends Controller
{
    private $xenditService;
    private $whatsappService;

    public function __construct(XenditService $xenditService, WhatsAppService $whatsappService)
    {
        $this->xenditService = $xenditService;
        $this->whatsappService = $whatsappService;
    }

    /**
     * Handle Xendit webhook callback
     */
    public function handleWebhook(Request $request)
    {
        try {
            // Get raw body for signature verification
            $rawBody = $request->getContent();
            $signature = $request->header('x-callback-token');

            Log::info('Xendit webhook received', [
                'headers' => $request->headers->all(),
                'body' => $rawBody,
                'signature' => $signature
            ]);

            // Verify webhook signature
            if (!$this->xenditService->verifyWebhookSignature($rawBody, $signature)) {
                Log::warning('Invalid webhook signature', [
                    'received_signature' => $signature,
                    'all_headers' => $request->headers->all(),
                    'body' => $rawBody
                ]);
                return response()->json(['error' => 'Invalid signature'], 401);
            }

            $payload = $request->all();
            
            // Process the webhook
            $processedUsers = $this->xenditService->processWebhook($payload);
            
            if (!$processedUsers) {
                return response()->json(['error' => 'Failed to process webhook'], 400);
            }

            // Ensure we have an array of users
            $users = is_array($processedUsers) ? $processedUsers : [$processedUsers];

            // Send WhatsApp notification if payment is successful
            if (strtolower($payload['status']) === 'paid') {
                foreach ($users as $user) {
                    $this->sendPaymentSuccessNotification($user, $payload);
                }
            } elseif (strtolower($payload['status']) === 'expired') {
                foreach ($users as $user) {
                    $this->sendPaymentExpiredNotification($user, $payload);
                }
            } elseif (strtolower($payload['status']) === 'failed') {
                foreach ($users as $user) {
                    $this->sendPaymentFailedNotification($user, $payload);
                }
            }

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            Log::error('Webhook handling error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

    /**
     * Send payment success notification via WhatsApp
     */
    private function sendPaymentSuccessNotification($user, $payload)
    {
        try {
            $whatsappNumber = $user->whatsapp_number ?: $user->phone;
            
            if (!$whatsappNumber) {
                Log::warning('No WhatsApp number for user', ['user_id' => $user->id]);
                return;
            }

            $amount = number_format($payload['amount'] ?? $user->payment_amount, 0, ',', '.');
            $paymentMethod = $payload['payment_method'] ?? 'Xendit';
            $paidAt = isset($payload['paid_at']) ? \Carbon\Carbon::parse($payload['paid_at'])->format('d/m/Y H:i') : now()->format('d/m/Y H:i');

            $message = "🎉 *PEMBAYARAN BERHASIL!* 🎉\n\n";
            $message .= "Halo *{$user->name}*,\n\n";
            $message .= "✅ Pembayaran registrasi Amazing Sultra Run Anda telah berhasil!\n\n";
            $message .= "📋 *Detail Pembayaran:*\n";
            $message .= "• Jumlah: Rp {$amount}\n";
            $message .= "• Metode: {$paymentMethod}\n";
            $message .= "• Waktu: {$paidAt}\n";
            $message .= "• ID Transaksi: {$payload['external_id']}\n\n";
            $message .= "🏃‍♂️ *Selamat bergabung dengan Amazing Sultra Run!*\n\n";
            $message .= "Anda sekarang adalah member resmi kami. Silakan login ke dashboard untuk melengkapi profil dan melihat jadwal latihan.\n\n";
            $message .= "🔗 Dashboard: " . url('/dashboard') . "\n\n";
            $message .= "Terima kasih dan selamat berlari! 🏃‍♀️💪";

            $queueId = $this->whatsappService->queueMessage($whatsappNumber, $message, 'high');

            Log::info('Payment success WhatsApp notification queued', [
                'user_id' => $user->id,
                'whatsapp_number' => $whatsappNumber,
                'queue_id' => $queueId
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to send payment success notification', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Send payment expired notification via WhatsApp
     */
    private function sendPaymentExpiredNotification($user, $payload)
    {
        try {
            $whatsappNumber = $user->whatsapp_number ?: $user->phone;
            
            if (!$whatsappNumber) {
                return;
            }

            $message = "⏰ *PEMBAYARAN KEDALUWARSA* ⏰\n\n";
            $message .= "Halo *{$user->name}*,\n\n";
            $message .= "❌ Link pembayaran registrasi Amazing Sultra Run Anda telah kedaluwarsa.\n\n";
            $message .= "Jangan khawatir! Anda masih bisa melakukan pembayaran dengan menghubungi admin atau login ke akun Anda untuk membuat pembayaran baru.\n\n";
            $message .= "📞 Hubungi Admin: +62811-4000-805\n";
            $message .= "🔗 Login: " . url('/login') . "\n\n";
            $message .= "Terima kasih! 🙏";

            $queueId = $this->whatsappService->queueMessage($whatsappNumber, $message, 'normal');

        } catch (\Exception $e) {
            Log::error('Failed to send payment expired notification', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Send payment failed notification via WhatsApp
     */
    private function sendPaymentFailedNotification($user, $payload)
    {
        try {
            $whatsappNumber = $user->whatsapp_number ?: $user->phone;
            
            if (!$whatsappNumber) {
                return;
            }

            $message = "❌ *PEMBAYARAN GAGAL* ❌\n\n";
            $message .= "Halo *{$user->name}*,\n\n";
            $message .= "Pembayaran registrasi Amazing Sultra Run Anda gagal diproses.\n\n";
            $message .= "Silakan coba lagi atau gunakan metode pembayaran yang berbeda.\n\n";
            $message .= "📞 Butuh bantuan? Hubungi Admin: +62811-4000-805\n";
            $message .= "🔗 Login: " . url('/login') . "\n\n";
            $message .= "Terima kasih! 🙏";

            $queueId = $this->whatsappService->queueMessage($whatsappNumber, $message, 'normal');

        } catch (\Exception $e) {
            Log::error('Failed to send payment failed notification', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
        }
    }
}
