<?php

namespace App\Services\Registration;

use App\Models\User;
use App\Models\TicketType;
use App\Services\XenditService;
use App\Services\WhatsAppService;
use App\Services\RandomPasswordService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class WakafRegistrationService
{
    private $xenditService;
    private $whatsappService;
    private $randomPasswordService;

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
     * Register user with Wakaf ticket type
     */
    public function registerWakaf(array $data): array
    {
        try {
            // Verify wakaf verification status
            if (!$this->isWakafVerified($data)) {
                throw new \Exception('Wakaf verification required before registration');
            }

            // Get Wakaf ticket type (assuming ticket_type_id = 4 for Wakaf)
            $wakafTicketType = TicketType::where('name', 'LIKE', '%wakaf%')
                ->orWhere('id', 4)
                ->first();
                
            if (!$wakafTicketType) {
                throw new \Exception('Wakaf ticket type not found');
            }

            // Override ticket type to Wakaf
            $data['ticket_type_id'] = $wakafTicketType->id;

            // Create user with Wakaf ticket
            $user = $this->createWakafUser($data);

            // Generate external ID for Wakaf
            $externalId = 'AMAZING-WAKAF-' . $user->id . '-' . time();
            $user->update(['xendit_external_id' => $externalId]);

            // Generate and send random password
            $passwordResult = $this->randomPasswordService->generateAndSendPassword(
                $user,
                $this->whatsappService,
                'wakaf'
            );

            // Create Xendit invoice for Wakaf registration
            $invoiceData = $this->xenditService->createInvoice($user);
            
            if ($invoiceData && isset($invoiceData['success']) && $invoiceData['success']) {
                $invoice = $invoiceData['data'];
                
                $user->update([
                    'xendit_invoice_id' => $invoice['id'],
                    'xendit_invoice_url' => $invoice['invoice_url'],
                    'xendit_url' => $invoice['invoice_url'], // For compatibility
                    'status' => 'registered'
                ]);

                // Send payment link via WhatsApp
                $this->sendWakafPaymentLink($user, $invoice['invoice_url']);

                Log::info('Wakaf registration completed successfully', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'registration_number' => $user->registration_number,
                    'xendit_external_id' => $user->xendit_external_id,
                    'invoice_id' => $invoice['id'],
                    'password_sent' => $passwordResult['password_sent'] ?? false
                ]);

                return [
                    'success' => true,
                    'user' => $user,
                    'invoice_url' => $invoice['invoice_url'],
                    'password_sent' => $passwordResult['password_sent'] ?? false
                ];
            } else {
                throw new \Exception('Failed to create Wakaf payment invoice');
            }

        } catch (\Exception $e) {
            Log::error('Wakaf registration failed', [
                'error' => $e->getMessage(),
                'data' => $data
            ]);

            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Verify SatuWakaf donation status
     */
    public function verifySatuWakafDonation(array $wakafData): array
    {
        try {
            $response = Http::timeout(30)
                ->post('https://api.satuwakafindonesia.id/donations/non-login', [
                    'nama_wakif' => $wakafData['nama_wakif'],
                    'email' => $wakafData['email'],
                    'no_hp' => $wakafData['no_hp'],
                    'jumlah_wakaf' => $wakafData['jumlah_wakaf']
                ]);

            if ($response->successful()) {
                $result = $response->json();
                
                Log::info('SatuWakaf API response', [
                    'status' => $result['status'] ?? 'unknown',
                    'email' => $wakafData['email']
                ]);

                if (isset($result['status']) && $result['status'] === 'VERIFIED') {
                    return [
                        'success' => true,
                        'verified' => true,
                        'donation_id' => $result['donation_id'] ?? null,
                        'qr_code' => $result['qr_code'] ?? null
                    ];
                } else {
                    return [
                        'success' => true,
                        'verified' => false,
                        'message' => 'Wakaf donation not yet verified'
                    ];
                }
            } else {
                throw new \Exception('SatuWakaf API request failed: ' . $response->status());
            }

        } catch (\Exception $e) {
            Log::error('SatuWakaf verification failed', [
                'error' => $e->getMessage(),
                'data' => $wakafData
            ]);

            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Check if wakaf is verified from session or other storage
     */
    private function isWakafVerified(array $data): bool
    {
        // Check if wakaf verification flag is present
        // This would typically come from session or localStorage validation
        return isset($data['wakaf_verified']) && $data['wakaf_verified'] === true;
    }

    /**
     * Create user with Wakaf-specific settings
     */
    private function createWakafUser(array $data): User
    {
        // Get Wakaf ticket type
        $ticketType = TicketType::findOrFail($data['ticket_type_id']);
        
        // Increment registered count
        $ticketType->increment('registered_count');
        
        // Prepare user data
        $userData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'whatsapp_number' => $data['whatsapp_number'],
            'date_of_birth' => $data['date_of_birth'],
            'gender' => $data['gender'],
            'blood_type_id' => $data['blood_type_id'],
            'emergency_contact_name' => $data['emergency_contact_name'],
            'emergency_contact_phone' => $data['emergency_contact_phone'],
            'address' => $data['address'],
            'race_category_id' => $data['race_category_id'],
            'jersey_size_id' => $data['jersey_size_id'],
            'ticket_type_id' => $data['ticket_type_id'],
            'registration_fee' => $ticketType->price,
            'registration_date' => now(),
            'status' => 'pending',
            'payment_status' => 'pending',
            'event_source_id' => $data['event_source_id'] ?? null,
            'registration_number' => $this->generateRegistrationNumber(),
            'password' => Hash::make(str()->random(12)), // Random password for Wakaf
            
            // Wakaf-specific fields
            'wakaf_verified' => true,
            'wakaf_verified_at' => now(),
            'wakaf_donation_id' => $data['wakaf_donation_id'] ?? null,
        ];
        
        return User::create($userData);
    }

    /**
     * Send Wakaf-specific payment link message
     */
    private function sendWakafPaymentLink(User $user, string $invoiceUrl): void
    {
        $message = "🕌 *" . strtoupper(config('event.name')) . " " . config('event.year') . " - WAKAF REGISTRATION*\n\n";
        $message .= "Assalamu'alaikum {$user->name}!\n\n";
        $message .= "🎉 Alhamdulillah! Registrasi Wakaf Anda berhasil!\n\n";
        $message .= "📋 *Detail Registrasi:*\n";
        $message .= "• Nama: {$user->name}\n";
        $message .= "• Email: {$user->email}\n";
        $message .= "• No. Registrasi: {$user->registration_number}\n";
        $message .= "• Kategori: Wakaf Participant\n\n";
        $message .= "💰 *Biaya Registrasi*: Rp " . number_format($user->registration_fee, 0, ',', '.') . "\n";
        $message .= "🔗 *Link Pembayaran*: {$invoiceUrl}\n\n";
        $message .= "✨ *Kelebihan Peserta Wakaf:*\n";
        $message .= "• Pahala berlipat dari donasi wakaf\n";
        $message .= "• Jersey eksklusif peserta wakaf\n";
        $message .= "• Doa khusus dari panitia\n\n";
        $message .= "Pembayaran dapat dilakukan melalui:\n";
        $message .= "• Transfer Bank\n• E-Wallet (OVO, DANA, GoPay)\n• Virtual Account\n• QRIS\n\n";
        $message .= "Barakallahu fiikum! 🤲\n";
        $message .= "Jazakumullahu khairan 🙏";

        $this->whatsappService->sendMessage($user->whatsapp_number, $message);
    }

    /**
     * Generate unique registration number
     */
    private function generateRegistrationNumber(): string
    {
        do {
            $registrationNumber = 'ASRW' . date('Y') . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        } while (User::where('registration_number', $registrationNumber)->exists());
        
        return $registrationNumber;
    }
}