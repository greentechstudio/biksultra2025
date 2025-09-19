<?php

namespace App\Services;

use App\Models\User;
use App\Models\WakafRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use App\Services\XenditService;
use App\Services\WhatsAppService;
use App\Services\RandomPasswordService;

class WakafRegistrationService
{
    protected $xenditService;
    protected $whatsappService;
    protected $randomPasswordService;

    public function __construct(
        XenditService $xenditService,
        WhatsAppService $whatsappService,
        RandomPasswordService $randomPasswordService
    ) {
        $this->xenditService = $xenditService;
        $this->whatsappService = $whatsappService;
        $this->randomPasswordService = $randomPasswordService;
    }

    /**
     * Process wakaf registration
     */
    public function processRegistration(Request $request): array
    {
        try {
            // Validate request data
            $validatedData = $this->validateRegistrationData($request);

            // Find or create user
            $user = $this->findOrCreateUser($validatedData);

            // Create wakaf donation via SatuWakaf API
            $wakafResponse = $this->createWakafDonation($validatedData);

            // Create wakaf registration record
            $wakafRegistration = $this->createWakafRegistration($user, $validatedData, $wakafResponse);

            // Send WhatsApp notification
            $this->sendWhatsAppNotification($user, $wakafResponse);

            return [
                'success' => true,
                'user_id' => $user->id,
                'wakaf_id' => $wakafRegistration->id,
                'donation_url' => $wakafResponse['donation_url'],
                'external_id' => $wakafResponse['external_id']
            ];

        } catch (\Exception $e) {
            Log::error('Wakaf registration failed', [
                'error' => $e->getMessage(),
                'request_data' => $request->all()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Validate registration data
     */
    protected function validateRegistrationData(Request $request): array
    {
        return $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'no_wa' => 'required|string|max:20',
            'jumlah_wakaf' => 'required|numeric|min:10000', // Minimum 10,000 IDR
            'doa_khusus' => 'nullable|string|max:500',
            'g-recaptcha-response' => 'required'
        ]);
    }

    /**
     * Find existing user or create new one
     */
    protected function findOrCreateUser(array $data): User
    {
        $user = User::where('email', $data['email'])->first();
        
        if (!$user) {
            $password = $this->randomPasswordService->generate();
            
            $user = User::create([
                'name' => $data['nama_lengkap'],
                'email' => $data['email'],
                'password' => Hash::make($password),
                'no_wa' => $data['no_wa'],
                'plain_password' => $password,
                'registration_type' => 'wakaf'
            ]);
        }

        return $user;
    }

    /**
     * Create wakaf donation via SatuWakaf API
     */
    protected function createWakafDonation(array $data): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . config('services.satuwakaf.api_key'),
                'Content-Type' => 'application/json'
            ])->post(config('services.satuwakaf.base_url') . '/donations', [
                'amount' => $data['jumlah_wakaf'],
                'donor_name' => $data['nama_lengkap'],
                'donor_email' => $data['email'],
                'donor_phone' => $data['no_wa'],
                'message' => $data['doa_khusus'] ?? '',
                'project_id' => config('services.satuwakaf.project_id'),
                'external_id' => 'WAKAF-' . time() . '-' . mt_rand(1000, 9999)
            ]);

            if (!$response->successful()) {
                throw new \Exception('Failed to create wakaf donation: ' . $response->body());
            }

            $responseData = $response->json();

            return [
                'donation_url' => $responseData['data']['payment_url'],
                'external_id' => $responseData['data']['external_id'],
                'donation_id' => $responseData['data']['id']
            ];

        } catch (\Exception $e) {
            Log::error('SatuWakaf API error', [
                'error' => $e->getMessage(),
                'data' => $data
            ]);
            throw $e;
        }
    }

    /**
     * Create wakaf registration record
     */
    protected function createWakafRegistration(User $user, array $data, array $wakafResponse): WakafRegistration
    {
        return WakafRegistration::create([
            'user_id' => $user->id,
            'amount' => $data['jumlah_wakaf'],
            'message' => $data['doa_khusus'] ?? '',
            'satuwakaf_external_id' => $wakafResponse['external_id'],
            'satuwakaf_donation_id' => $wakafResponse['donation_id'],
            'status' => 'pending'
        ]);
    }

    /**
     * Send WhatsApp notification
     */
    protected function sendWhatsAppNotification(User $user, array $wakafData): void
    {
        try {
            $amount = number_format($user->wakafRegistrations()->latest()->first()->amount, 0, ',', '.');
            $message = "Halo {$user->name}! Terima kasih telah mendaftar wakaf sebesar Rp {$amount}. Silakan lakukan pembayaran melalui link berikut: {$wakafData['donation_url']}";
            
            $this->whatsappService->sendMessage($user->no_wa, $message);
        } catch (\Exception $e) {
            Log::warning('WhatsApp notification failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Process wakaf webhook from SatuWakaf
     */
    public function processWebhook(Request $request): array
    {
        try {
            $payload = $request->all();
            
            // Validate webhook signature
            if (!$this->validateWebhookSignature($request)) {
                throw new \Exception('Invalid webhook signature');
            }

            $externalId = $payload['external_id'];
            $status = $payload['status'];

            // Find wakaf registration
            $wakafRegistration = WakafRegistration::where('satuwakaf_external_id', $externalId)->first();
            
            if (!$wakafRegistration) {
                throw new \Exception('Wakaf registration not found');
            }

            // Update status
            $wakafRegistration->update(['status' => $status]);

            // If paid, send confirmation message
            if ($status === 'paid') {
                $this->sendPaymentConfirmation($wakafRegistration->user);
            }

            return ['success' => true];

        } catch (\Exception $e) {
            Log::error('Wakaf webhook processing failed', [
                'error' => $e->getMessage(),
                'payload' => $request->all()
            ]);

            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Validate webhook signature
     */
    protected function validateWebhookSignature(Request $request): bool
    {
        // Implementation depends on SatuWakaf webhook signature validation
        $signature = $request->header('X-Signature');
        $webhookSecret = config('services.satuwakaf.webhook_secret');
        
        $expectedSignature = hash_hmac('sha256', $request->getContent(), $webhookSecret);
        
        return hash_equals($expectedSignature, $signature);
    }

    /**
     * Send payment confirmation via WhatsApp
     */
    protected function sendPaymentConfirmation(User $user): void
    {
        try {
            $wakafRegistration = $user->wakafRegistrations()->latest()->first();
            $amount = number_format($wakafRegistration->amount, 0, ',', '.');
            
            $message = "Alhamdulillah! Pembayaran wakaf Anda sebesar Rp {$amount} telah berhasil. Terima kasih atas kontribusi Anda. Semoga Allah SWT membalas kebaikan Anda.";
            
            $this->whatsappService->sendMessage($user->no_wa, $message);
        } catch (\Exception $e) {
            Log::warning('Payment confirmation WhatsApp failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
        }
    }
}