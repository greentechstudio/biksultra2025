<?php

namespace App\Services\Registration;

use App\Models\User;
use App\Models\TicketType;
use App\Services\XenditService;
use App\Services\WhatsAppService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class CollectiveRegistrationService
{
    private $xenditService;
    private $whatsappService;

    public function __construct(
        XenditService $xenditService,
        WhatsAppService $whatsappService
    ) {
        $this->xenditService = $xenditService;
        $this->whatsappService = $whatsappService;
    }

    /**
     * Register multiple users as collective group
     */
    public function registerCollectiveGroup(array $participants): array
    {
        DB::beginTransaction();
        
        try {
            $users = [];
            $totalAmount = 0;
            $participantDetails = [];
            
            // Create all users first
            foreach ($participants as $index => $participantData) {
                $user = $this->createCollectiveUser($participantData, $index === 0);
                $users[] = $user;
                $totalAmount += $user->registration_fee;
                
                $participantDetails[] = [
                    'name' => $user->name,
                    'email' => $user->email,
                    'whatsapp_number' => $user->whatsapp_number,
                    'race_category' => $user->raceCategory->name ?? 'Unknown',
                    'jersey_size' => $user->jerseySize->name ?? 'Unknown',
                    'amount' => $user->registration_fee
                ];
            }
            
            // Generate collective external ID
            $primaryUser = $users[0];
            $externalId = 'AMAZING-COLLECTIVE-' . $primaryUser->id . '-' . time();
            
            // Update all users with same external ID
            foreach ($users as $user) {
                $user->update(['xendit_external_id' => $externalId]);
            }
            
            // Create collective invoice
            $description = config('event.name') . " " . config('event.year') . " - Collective Registration (" . count($users) . " participants)";
            $paymentResult = $this->xenditService->createCollectiveInvoice(
                $primaryUser,
                $participantDetails,
                $totalAmount,
                $description
            );
            
            if ($paymentResult['success']) {
                // Update all users with invoice details
                foreach ($users as $user) {
                    $user->update([
                        'xendit_invoice_id' => $paymentResult['invoice_id'],
                        'xendit_invoice_url' => $paymentResult['invoice_url'],
                        'payment_description' => $description,
                        'status' => 'registered'
                    ]);
                }
                
                // Send payment link to group leader only
                $this->sendCollectivePaymentLinkToLeader($users, $paymentResult['invoice_url'], $participantDetails, $totalAmount);
                
                DB::commit();
                
                Log::info('Collective registration completed successfully', [
                    'primary_user_id' => $primaryUser->id,
                    'participant_count' => count($users),
                    'total_amount' => $totalAmount,
                    'external_id' => $externalId
                ]);
                
                return [
                    'success' => true,
                    'users' => $users,
                    'primary_user' => $primaryUser,
                    'invoice_url' => $paymentResult['invoice_url'],
                    'total_amount' => $totalAmount
                ];
            } else {
                throw new \Exception('Failed to create collective payment invoice');
            }
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Collective registration failed', [
                'error' => $e->getMessage(),
                'participant_count' => count($participants)
            ]);
            
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Register collective group from CSV import (Admin)
     */
    public function registerCollectiveFromCSV(array $importedUsers, string $groupName = ''): array
    {
        DB::beginTransaction();
        
        try {
            $users = [];
            $totalAmount = 0;
            $participantDetails = [];
            
            // Process imported users
            foreach ($importedUsers as $index => $userData) {
                $user = $this->createCollectiveUserFromImport($userData, $index === 0);
                $users[] = $user;
                $totalAmount += $user->registration_fee;
                
                $participantDetails[] = [
                    'name' => $user->name,
                    'email' => $user->email,
                    'whatsapp_number' => $user->whatsapp_number,
                    'race_category' => $user->raceCategory->name ?? 'Unknown',
                    'jersey_size' => $user->jerseySize->name ?? 'Unknown',
                    'amount' => $user->registration_fee
                ];
            }
            
            // Generate admin collective external ID
            $primaryUser = $users[0];
            $externalId = 'AMAZING-ADMIN-COLLECTIVE-' . $primaryUser->id . '-' . time();
            
            // Update all users with same external ID
            foreach ($users as $user) {
                $user->update(['xendit_external_id' => $externalId]);
            }
            
            // Create admin collective invoice
            $invoiceUrl = $this->xenditService->createAdminCollectiveInvoice(
                $users,
                $groupName ?: 'Admin Import Group'
            );
            
            if ($invoiceUrl) {
                DB::commit();
                
                Log::info('Admin collective registration completed successfully', [
                    'primary_user_id' => $primaryUser->id,
                    'participant_count' => count($users),
                    'total_amount' => $totalAmount,
                    'external_id' => $externalId,
                    'group_name' => $groupName
                ]);
                
                return [
                    'success' => true,
                    'users' => $users,
                    'primary_user' => $primaryUser,
                    'invoice_url' => $invoiceUrl,
                    'total_amount' => $totalAmount
                ];
            } else {
                throw new \Exception('Failed to create admin collective payment invoice');
            }
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Admin collective registration failed', [
                'error' => $e->getMessage(),
                'participant_count' => count($importedUsers),
                'group_name' => $groupName
            ]);
            
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Create collective user from manual form data
     */
    private function createCollectiveUser(array $data, bool $isPrimary): User
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
            'password' => Hash::make(str()->random(12)), // Random password for collective
        ];
        
        return User::create($userData);
    }

    /**
     * Create collective user from CSV import data
     */
    private function createCollectiveUserFromImport(array $data, bool $isPrimary): User
    {
        // Process CSV data similar to createCollectiveUser
        // This would handle the CSV-specific data format
        return $this->createCollectiveUser($data, $isPrimary);
    }

    /**
     * Send collective payment link to group leader only
     */
    private function sendCollectivePaymentLinkToLeader(array $users, string $invoiceUrl, array $participantDetails, float $totalAmount): void
    {
        $primaryUser = $users[0];
        $participantCount = count($users);
        
        $message = "🏃‍♂️ *" . strtoupper(config('event.name')) . " " . config('event.year') . " - COLLECTIVE REGISTRATION*\n\n";
        $message .= "Halo {$primaryUser->name}!\n\n";
        $message .= "✅ Registrasi kolektif berhasil untuk {$participantCount} peserta!\n\n";
        $message .= "👥 *DAFTAR PESERTA:*\n";
        
        foreach ($participantDetails as $index => $participant) {
            $message .= ($index + 1) . ". {$participant['name']}\n";
            $message .= "   📧 {$participant['email']}\n";
            $message .= "   💰 Rp " . number_format($participant['amount'], 0, ',', '.') . "\n\n";
        }
        
        $message .= "💰 *TOTAL PEMBAYARAN*: Rp " . number_format($totalAmount, 0, ',', '.') . "\n\n";
        $message .= "🔗 *Link Pembayaran*: {$invoiceUrl}\n\n";
        $message .= "Pembayaran dapat dilakukan melalui:\n";
        $message .= "• Transfer Bank\n• E-Wallet (OVO, DANA, GoPay)\n• Virtual Account\n• QRIS\n\n";
        $message .= "⚠️ *PENTING*: Setelah pembayaran berhasil, semua peserta akan mendapatkan konfirmasi.\n\n";
        $message .= "Terima kasih! 🙏";

        $this->whatsappService->sendMessage($primaryUser->whatsapp_number, $message);
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
}