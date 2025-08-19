<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    private $apiKey;
    private $sender;
    private $apiUrl;
    private $queueService;

    public function __construct()
    {
        $this->apiKey = env('WHATSAPP_API_KEY', 'tZiKYy1sHXasOj0hDGZnRfAnAYo2Ec');
        $this->sender = env('WHATSAPP_SENDER', '628114040707');
        $this->apiUrl = 'https://wamd.system112.org/send-message';
        $this->queueService = app(WhatsAppQueueService::class);
    }

    /**
     * Send a WhatsApp message (direct)
     */
    public function sendMessage($phoneNumber, $message)
    {
        try {
            $response = Http::timeout(30)->post($this->apiUrl, [
                'api_key' => $this->apiKey,
                'sender' => $this->sender,
                'number' => $phoneNumber,
                'message' => $message
            ]);

            if ($response->successful() && $response->json('status') === true) {
                Log::info('WhatsApp message sent successfully', [
                    'phone_number' => $phoneNumber,
                    'message_length' => strlen($message)
                ]);
                return true;
            } else {
                Log::error('Failed to send WhatsApp message', [
                    'phone_number' => $phoneNumber,
                    'response' => $response->json()
                ]);
                return false;
            }
        } catch (\Exception $e) {
            Log::error('Exception sending WhatsApp message', [
                'phone_number' => $phoneNumber,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Queue a WhatsApp message (recommended for bulk messages)
     */
    public function queueMessage($phoneNumber, $message, $priority = 'normal')
    {
        $queueId = $this->queueService->addToQueue($phoneNumber, $message, $priority);
        
        Log::info('WhatsApp message queued', [
            'queue_id' => $queueId,
            'phone_number' => $phoneNumber,
            'priority' => $priority
        ]);
        
        return $queueId;
    }

    /**
     * Validate if a WhatsApp number exists
     */
    public function validateNumber($phoneNumber)
    {
        try {
            $apiUrl = 'https://wamd.system112.org/check-number';
            
            $response = Http::timeout(10)->get($apiUrl, [
                'api_key' => $this->apiKey,
                'sender' => $this->sender,
                'number' => $phoneNumber
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                Log::info('WhatsApp API validation response', [
                    'phone' => $phoneNumber,
                    'response' => $data
                ]);
                
                // Handle API response format: {"status": true, "data": {"jid": "...", "exists": true}}
                if (isset($data['status']) && $data['status'] === true) {
                    if (is_array($data['data']) && isset($data['data']['exists']) && $data['data']['exists'] === true) {
                        return true;
                    } else if ($data['data'] === true && !is_array($data['data'])) {
                        // Handle case where data is just boolean true without jid/lid (10 digit number issue)
                        // Try adding 0 after 62 for 10 digit numbers
                        Log::info('WhatsApp validation returned true without jid/lid, trying with 0 prefix', [
                            'original_number' => $phoneNumber
                        ]);
                        
                        // Check if this is a 10 digit number (original 10 digits becomes 62 + 10 = 12, but for 0-prefixed becomes 62 + 9 = 11)
                        if ((strlen($phoneNumber) === 11 || strlen($phoneNumber) === 12) && str_starts_with($phoneNumber, '62')) {
                            $newFormattedNumber = '620' . substr($phoneNumber, 2);
                            
                            Log::info('Retrying WhatsApp validation with 0 prefix', [
                                'original' => $phoneNumber,
                                'new_format' => $newFormattedNumber
                            ]);
                            
                            // Retry validation with the new format
                            $retryResponse = Http::timeout(10)->get($apiUrl, [
                                'api_key' => $this->apiKey,
                                'sender' => $this->sender,
                                'number' => $newFormattedNumber
                            ]);
                            
                            if ($retryResponse->successful()) {
                                $retryData = $retryResponse->json();
                                
                                Log::info('WhatsApp API retry response received', [
                                    'phone' => $newFormattedNumber,
                                    'response' => $retryData
                                ]);
                                
                                if (isset($retryData['status']) && $retryData['status'] === true) {
                                    if (is_array($retryData['data']) && isset($retryData['data']['exists']) && $retryData['data']['exists'] === true) {
                                        return true;
                                    }
                                }
                            }
                        }
                        
                        // If retry failed or not applicable, return false
                        return false;
                    }
                }
                
                return false;
            }
            
            return false; // API call failed
        } catch (\Exception $e) {
            Log::error('Exception validating WhatsApp number', [
                'phone_number' => $phoneNumber,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Format phone number to international format
     */
    public function formatPhoneNumber($phoneNumber)
    {
        // Remove all non-numeric characters
        $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);
        
        // Handle Indonesian numbers
        if (substr($phoneNumber, 0, 1) === '0') {
            $phoneNumber = '62' . substr($phoneNumber, 1);
        } elseif (substr($phoneNumber, 0, 2) !== '62') {
            $phoneNumber = '62' . $phoneNumber;
        }
        
        return $phoneNumber;
    }

    /**
     * Send payment link message (using queue)
     */
    public function sendPaymentLink($user, $invoiceUrl)
    {
        $amount = $user->registration_fee ? number_format($user->registration_fee, 0, ',', '.') : 'Sesuai kategori';
        
        $message = "🎯 *LINK PEMBAYARAN REGISTRASI* 🎯\n\n";
        $message .= "Halo *{$user->name}*,\n\n";
        $message .= "Terima kasih telah mendaftar di Amazing Sultra Run! 🏃‍♂️\n\n";
        $message .= "📋 *Detail Pembayaran:*\n";
        $message .= "• Nama: {$user->name}\n";
        $message .= "• Kategori: {$user->race_category}\n";
        $message .= "• Biaya Registrasi: Rp {$amount}\n";
        $message .= "• Berlaku selama: 24 jam\n\n";
        $message .= "💳 *Link Pembayaran:*\n";
        $message .= "{$invoiceUrl}\n\n";
        $message .= "⚠️ *Penting:*\n";
        $message .= "• Link pembayaran akan kedaluwarsa dalam 24 jam\n";
        $message .= "• Anda akan mendapat konfirmasi otomatis setelah pembayaran berhasil\n";
        $message .= "• Membership akan aktif setelah pembayaran terkonfirmasi\n\n";
        $message .= "📞 Butuh bantuan? Hubungi: +62811-4000-805\n\n";
        $message .= "Terima kasih! 🙏\n\n";
        $message .= "_Pesan ini dikirim otomatis pada: " . now()->format('d/m/Y H:i') . "_";

        // Queue message with high priority for payment links
        return $this->queueMessage($user->whatsapp_number, $message, 'high');
    }

    /**
     * Send registration activation message (using queue)
     */
    public function sendActivationMessage($user)
    {
        $message = "🎉 *SELAMAT DATANG DI AMAZING SULTRA RUN!* 🎉\n\n";
        $message .= "Halo *{$user->name}*,\n\n";
        $message .= "Akun Anda telah berhasil dibuat! 🎯\n\n";
        $message .= "📱 *Detail Login:*\n";
        $message .= "• Email: {$user->email}\n";
        $message .= "• Password: [yang Anda buat saat registrasi]\n\n";
        $message .= "🔗 *Link Dashboard:*\n";
        $message .= url('/dashboard') . "\n\n";
        $message .= "📋 *Langkah Selanjutnya:*\n";
        $message .= "1. Login ke dashboard\n";
        $message .= "2. Lengkapi profil Anda\n";
        $message .= "3. Lakukan pembayaran registrasi\n";
        $message .= "4. Mulai bergabung dengan aktivitas kami!\n\n";
        $message .= "💪 Selamat bergabung dan selamat berlari!\n\n";
        $message .= "📞 Butuh bantuan? Hubungi: +62811-4000-805\n\n";
        $message .= "_Pesan ini dikirim otomatis pada: " . now()->format('d/m/Y H:i') . "_";

        // Queue message with normal priority for activation
        return $this->queueMessage($user->whatsapp_number, $message, 'normal');
    }

    /**
     * Send payment success notification (using queue)
     */
    public function sendPaymentSuccessNotification($user, $paymentData)
    {
        try {
            $amount = number_format($paymentData['amount'] ?? $user->payment_amount, 0, ',', '.');
            $paymentMethod = $paymentData['payment_method'] ?? $user->payment_method ?? 'Xendit';
            $paidAt = isset($paymentData['paid_at']) ? 
                \Carbon\Carbon::parse($paymentData['paid_at'])->format('d/m/Y H:i') : 
                ($user->payment_confirmed_at ? $user->payment_confirmed_at->format('d/m/Y H:i') : now()->format('d/m/Y H:i'));

            $message = "🎉 *PEMBAYARAN BERHASIL!* 🎉\n\n";
            $message .= "Halo *{$user->name}*,\n\n";
            $message .= "✅ Pembayaran registrasi Amazing Sultra Run Anda telah berhasil!\n\n";
            $message .= "📋 *Detail Pembayaran:*\n";
            $message .= "• Nama: {$user->name}\n";
            $message .= "• Kategori: {$user->race_category}\n";
            $message .= "• Jumlah: Rp {$amount}\n";
            $message .= "• Metode: {$paymentMethod}\n";
            $message .= "• Waktu: {$paidAt}\n";
            $message .= "• ID Transaksi: " . ($paymentData['external_id'] ?? $user->external_id ?? 'N/A') . "\n\n";
            $message .= "🏃‍♂️ *Selamat datang di komunitas Amazing Sultra Run!*\n\n";
            $message .= "Anda sekarang adalah member resmi kami. Silakan login ke dashboard untuk melengkapi profil dan melihat jadwal latihan.\n\n";
            $message .= "🔗 *Link Dashboard:*\n";
            $message .= url('/dashboard') . "\n\n";
            $message .= "📋 *Langkah Selanjutnya:*\n";
            $message .= "1. Login ke dashboard\n";
            $message .= "2. Lengkapi profil Anda jika belum\n";
            $message .= "3. Cek jadwal latihan dan event\n";
            $message .= "4. Mulai bergabung dengan aktivitas kami!\n\n";
            $message .= "💪 Selamat bergabung dan selamat berlari!\n\n";
            $message .= "📞 Butuh bantuan? Hubungi: +62811-4000-805\n\n";
            $message .= "_Pesan ini dikirim otomatis pada: " . now()->format('d/m/Y H:i') . "_";

            // Queue message with high priority for payment success
            $queueId = $this->queueMessage($user->whatsapp_number, $message, 'high');
            
            return [
                'success' => true,
                'message' => 'Payment success notification queued successfully',
                'queue_id' => $queueId
            ];
            
        } catch (\Exception $e) {
            Log::error('Exception queueing payment success notification', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
            
            return [
                'success' => false,
                'message' => 'Exception: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Get queue status
     */
    public function getQueueStatus()
    {
        return $this->queueService->getQueueStatus();
    }

    /**
     * Clear queue (emergency use)
     */
    public function clearQueue()
    {
        return $this->queueService->clearQueue();
    }

    /**
     * Force process queue
     */
    public function forceProcessQueue()
    {
        return $this->queueService->forceProcessQueue();
    }
}
