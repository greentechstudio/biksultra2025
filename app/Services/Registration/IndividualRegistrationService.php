<?php

namespace App\Services\Registration;

use App\Models\User;
use App\Models\TicketType;
use App\Services\XenditService;
use App\Services\RandomPasswordService;
use App\Services\WhatsAppService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class IndividualRegistrationService
{
    private $xenditService;
    private $randomPasswordService;
    private $whatsappService;

    public function __construct(
        XenditService $xenditService,
        RandomPasswordService $randomPasswordService,
        WhatsAppService $whatsappService
    ) {
        $this->xenditService = $xenditService;
        $this->randomPasswordService = $randomPasswordService;
        $this->whatsappService = $whatsappService;
    }

    /**
     * Register individual user with manual password
     */
    public function registerWithManualPassword(array $data): array
    {
        try {
            // Create user
            $user = $this->createUser($data, 'manual');
            
            // Generate external ID
            $externalId = 'AMAZING-REG-' . $user->id . '-' . time();
            $user->update(['xendit_external_id' => $externalId]);
            
            // Create Xendit invoice
            $invoice = $this->xenditService->createInvoice($user);
            
            if ($invoice && isset($invoice['success']) && $invoice['success']) {
                $invoiceData = $invoice['data'];
                $user->update([
                    'xendit_invoice_id' => $invoiceData['id'],
                    'xendit_invoice_url' => $invoiceData['invoice_url'],
                    'status' => 'registered'
                ]);
                
                // Send payment link via WhatsApp
                $this->sendPaymentLink($user, $invoiceData['invoice_url']);
                
                Log::info('Individual registration completed successfully', [
                    'user_id' => $user->id,
                    'registration_type' => 'manual_password'
                ]);
                
                return [
                    'success' => true,
                    'user' => $user,
                    'invoice_url' => $invoiceData['invoice_url']
                ];
            }
            
            throw new \Exception('Failed to create payment invoice');
            
        } catch (\Exception $e) {
            Log::error('Individual registration failed', [
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
     * Register individual user with random password
     */
    public function registerWithRandomPassword(array $data): array
    {
        try {
            // Create user
            $user = $this->createUser($data, 'random');
            
            // Generate and send random password
            $passwordResult = $this->randomPasswordService->generateAndSendPassword(
                $user,
                $this->whatsappService,
                'registration'
            );
            
            if (!$passwordResult['success']) {
                throw new \Exception('Failed to generate and send password');
            }
            
            // Generate external ID
            $externalId = 'AMAZING-REG-' . $user->id . '-' . time();
            $user->update(['xendit_external_id' => $externalId]);
            
            // Create Xendit invoice
            $invoice = $this->xenditService->createInvoice($user);
            
            if ($invoice && isset($invoice['success']) && $invoice['success']) {
                $invoiceData = $invoice['data'];
                $user->update([
                    'xendit_invoice_id' => $invoiceData['id'],
                    'xendit_invoice_url' => $invoiceData['invoice_url'],
                    'status' => 'registered'
                ]);
                
                // Send payment link via WhatsApp
                $this->sendPaymentLink($user, $invoiceData['invoice_url']);
                
                Log::info('Individual registration with random password completed', [
                    'user_id' => $user->id,
                    'registration_type' => 'random_password',
                    'password_sent' => $passwordResult['password_sent']
                ]);
                
                return [
                    'success' => true,
                    'user' => $user,
                    'invoice_url' => $invoiceData['invoice_url'],
                    'password_sent' => $passwordResult['password_sent']
                ];
            }
            
            throw new \Exception('Failed to create payment invoice');
            
        } catch (\Exception $e) {
            Log::error('Individual registration with random password failed', [
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
     * Create user with validated data
     */
    private function createUser(array $data, string $passwordType): User
    {
        // Get ticket type for price calculation
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
        ];
        
        // Set password based on type
        if ($passwordType === 'manual') {
            $userData['password'] = Hash::make($data['password']);
        } else {
            // Random password will be set by RandomPasswordService
            $userData['password'] = Hash::make(str()->random(12));
        }
        
        return User::create($userData);
    }

    /**
     * Generate unique registration number
     */
    private function generateRegistrationNumber(): string
    {
        do {
            $registrationNumber = 'ASR' . date('Y') . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        } while (User::where('registration_number', $registrationNumber)->exists());
        
        return $registrationNumber;
    }

    /**
     * Send payment link via WhatsApp
     */
    private function sendPaymentLink(User $user, string $invoiceUrl): void
    {
        $message = "🏃‍♂️ *" . strtoupper(config('event.name')) . " " . config('event.year') . "*\n\n";
        $message .= "Halo {$user->name}!\n\n";
        $message .= "Registrasi Anda berhasil! Silakan lakukan pembayaran:\n\n";
        $message .= "💰 *Jumlah*: Rp " . number_format($user->registration_fee, 0, ',', '.') . "\n";
        $message .= "🔗 *Link Pembayaran*: {$invoiceUrl}\n\n";
        $message .= "Pembayaran dapat dilakukan melalui:\n";
        $message .= "• Transfer Bank\n• E-Wallet (OVO, DANA, GoPay)\n• Virtual Account\n• QRIS\n\n";
        $message .= "Terima kasih! 🙏";

        $this->whatsappService->sendMessage($user->whatsapp_number, $message);
    }
}