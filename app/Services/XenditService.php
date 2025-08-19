<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class XenditService
{
    private $baseUrl;
    private $apiKey;
    private $webhookToken;

    public function __construct()
    {
        $this->baseUrl = config('xendit.base_url');
        $this->apiKey = config('xendit.secret_key');
        $this->webhookToken = config('xendit.webhook_token');
        
        // Validate required configurations
        if (empty($this->apiKey)) {
            throw new \Exception('Xendit secret key is not configured. Please set XENDIT_SECRET_KEY in your .env file.');
        }
        
        if (empty($this->baseUrl)) {
            throw new \Exception('Xendit base URL is not configured. Please set XENDIT_BASE_URL in your .env file.');
        }
    }

    /**
     * Create invoice for user registration payment
     * SECURITY: Price is ALWAYS taken from ticket_types.price database
     * Any provided amount parameter is validated against database price
     */
    public function createInvoice(User $user, $amount = null, $description = 'Amazing Sultra Run Registration Fee')
    {
        // SECURITY: Validate first - these exceptions should NOT be caught
        if (empty($this->apiKey)) {
            throw new \Exception('Xendit API key is not configured');
        }
        
        // SECURITY: Always validate price against database - NEVER trust any input
        $officialAmount = $this->validateAndGetOfficialPrice($user);
        
        // If amount is provided, validate it matches official price
        if ($amount !== null) {
            // Convert both to float for accurate comparison
            $providedAmount = (float) $amount;
            $officialAmountFloat = (float) $officialAmount;
            
            if (abs($providedAmount - $officialAmountFloat) > 0.01) { // Allow for floating point precision
                \Log::critical('SECURITY ALERT: Price manipulation attempt detected', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'provided_amount' => $providedAmount,
                    'official_amount' => $officialAmountFloat,
                    'difference' => $providedAmount - $officialAmountFloat,
                    'ip' => request()->ip() ?? 'unknown',
                    'user_agent' => request()->userAgent() ?? 'unknown',
                    'timestamp' => now(),
                    'action' => 'transaction_blocked'
                ]);
                throw new \Exception('Security violation: Price manipulation detected. Transaction blocked for security reasons.');
            }
            
            \Log::info('Amount validation passed', [
                'user_id' => $user->id,
                'provided_amount' => $providedAmount,
                'official_amount' => $officialAmountFloat,
                'status' => 'validated'
            ]);
        }
        
        // SECURITY: Always use official price from database (never trust input)
        $amount = (float) $officialAmount;
        
        // Additional security checks
        if ($amount <= 0 || $amount > 10000000) { // Max 10 million IDR
            \Log::critical('SECURITY ALERT: Invalid amount detected', [
                'user_id' => $user->id,
                'amount' => $amount,
                'category' => $user->race_category,
                'action' => 'transaction_blocked'
            ]);
            throw new \Exception('Security violation: Invalid amount detected.');
        }
        
        // Now proceed with invoice creation
        try {
            
            $externalId = 'AMAZING-REG-' . $user->id . '-' . time();
            
            $payload = [
                'external_id' => $externalId,
                'amount' => $amount,
                'description' => $description,
                'invoice_duration' => 86400, // 24 hours
                'customer' => [
                    'given_names' => $user->name,
                    'email' => $user->email,
                    'mobile_number' => $user->phone ?: $user->whatsapp_number,
                ],
                'customer_notification_preference' => [
                    'invoice_created' => ['whatsapp', 'sms', 'email'],
                    'invoice_reminder' => ['whatsapp', 'sms', 'email'],
                    'invoice_paid' => ['whatsapp', 'sms', 'email'],
                    'invoice_expired' => ['whatsapp', 'sms', 'email']
                ],
                'success_redirect_url' => url('/payment/success'),
                'failure_redirect_url' => url('/payment/failed'),
                'currency' => 'IDR',
                'items' => [
                    [
                        'name' => 'Amazing Sultra Run Registration - ' . $user->race_category,
                        'quantity' => 1,
                        'price' => $amount,
                        'category' => 'Registration Fee'
                    ]
                ],
                'fees' => [
                    [
                        'type' => 'ADMIN',
                        'value' => 0
                    ]
                ]
            ];

            $response = Http::withBasicAuth($this->apiKey, '')
                ->withHeaders([
                    'Content-Type' => 'application/json'
                ])
                ->post($this->baseUrl . '/v2/invoices', $payload);

            if ($response->successful()) {
                $invoiceData = $response->json();
                
                // Update user with Xendit data
                $user->update([
                    'xendit_invoice_id' => $invoiceData['id'],
                    'xendit_invoice_url' => $invoiceData['invoice_url'],
                    'xendit_external_id' => $externalId,
                    'payment_amount' => $amount,
                    'payment_status' => 'pending',
                    'payment_requested_at' => now()
                ]);

                Log::info('Xendit invoice created successfully', [
                    'user_id' => $user->id,
                    'invoice_id' => $invoiceData['id'],
                    'external_id' => $externalId
                ]);

                return [
                    'success' => true,
                    'data' => $invoiceData,
                    'invoice_url' => $invoiceData['invoice_url']
                ];
            } else {
                Log::error('Xendit invoice creation failed', [
                    'user_id' => $user->id,
                    'response' => $response->json(),
                    'status' => $response->status()
                ]);

                return [
                    'success' => false,
                    'error' => 'Failed to create payment invoice',
                    'details' => $response->json()
                ];
            }
        } catch (\Exception $e) {
            Log::error('Xendit invoice creation exception', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'error' => 'Payment service error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Get invoice details
     */
    public function getInvoice($invoiceId)
    {
        try {
            // Additional validation
            if (empty($this->apiKey)) {
                throw new \Exception('Xendit API key is not configured');
            }
            
            $response = Http::withBasicAuth($this->apiKey, '')
                ->get($this->baseUrl . '/v2/invoices/' . $invoiceId);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json()
                ];
            }

            return [
                'success' => false,
                'error' => 'Failed to get invoice details'
            ];
        } catch (\Exception $e) {
            Log::error('Get invoice error', [
                'invoice_id' => $invoiceId,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Verify webhook signature
     */
    public function verifyWebhookSignature($rawBody, $signature)
    {
        // If webhook token is not set, skip verification (for testing)
        if (empty($this->webhookToken) || $this->webhookToken === 'your_webhook_token_here') {
            Log::warning('Webhook token not configured properly, skipping signature verification');
            return true;
        }
        
        // Log for debugging
        Log::info('Webhook signature verification', [
            'received_signature' => $signature,
            'expected_token' => $this->webhookToken,
            'signature_length' => strlen($signature ?? ''),
            'token_length' => strlen($this->webhookToken)
        ]);
        
        // Xendit uses simple token comparison, not HMAC
        // The X-CALLBACK-TOKEN header should match our webhook token exactly
        return hash_equals($this->webhookToken, $signature ?? '');
    }

    /**
     * Process webhook callback
     */
    public function processWebhook($payload)
    {
        try {
            Log::info('Processing Xendit webhook', ['payload' => $payload]);

            if (!isset($payload['external_id']) || !isset($payload['status'])) {
                Log::warning('Invalid webhook payload', ['payload' => $payload]);
                return false;
            }

            // For collective payments, find all users with the same invoice_id
            $users = User::where(function($query) use ($payload) {
                $query->where('xendit_external_id', $payload['external_id'])
                      ->orWhere('xendit_invoice_id', $payload['id'] ?? null);
            })->get();
            
            if ($users->isEmpty()) {
                Log::warning('No users found for external_id or invoice_id', [
                    'external_id' => $payload['external_id'],
                    'invoice_id' => $payload['id'] ?? null
                ]);
                return false;
            }

            Log::info('Users found for webhook processing', [
                'user_count' => $users->count(),
                'first_user_id' => $users->first()->id,
                'webhook_status' => $payload['status'],
                'is_collective' => $users->count() > 1
            ]);

            $processedUsers = [];

            // Update all matching users (for collective payments)
            foreach ($users as $user) {
                Log::info('Processing user in webhook', [
                    'user_id' => $user->id,
                    'user_email' => $user->email,
                    'current_status' => $user->status,
                    'current_payment_status' => $user->payment_status,
                    'webhook_status' => $payload['status']
                ]);

                // Update user payment status
                $updateData = [
                    'xendit_callback_data' => $payload,
                    'payment_status' => strtolower($payload['status'])
                ];

                if (strtolower($payload['status']) === 'paid') {
                    $updateData['payment_confirmed'] = true;
                    $updateData['payment_confirmed_at'] = now();
                    $updateData['paid_at'] = now();
                    $updateData['payment_method'] = $payload['payment_method'] ?? 'xendit';
                    $updateData['xendit_payment_id'] = $payload['id'] ?? null;
                    $updateData['status'] = 'active';
                    
                    Log::info('Payment confirmed, updating user to active status', [
                        'user_id' => $user->id,
                        'payment_method' => $updateData['payment_method'],
                        'xendit_payment_id' => $updateData['xendit_payment_id']
                    ]);
                } elseif (strtolower($payload['status']) === 'expired') {
                    $updateData['status'] = 'expired';
                    Log::info('Payment expired, updating user status', ['user_id' => $user->id]);
                } elseif (strtolower($payload['status']) === 'failed') {
                    $updateData['status'] = 'failed';
                    Log::info('Payment failed, updating user status', ['user_id' => $user->id]);
                }

                $user->update($updateData);
                $processedUsers[] = $user;

                Log::info('User payment status updated successfully', [
                    'user_id' => $user->id,
                    'old_status' => $user->getOriginal('status'),
                    'new_status' => $user->status,
                    'old_payment_status' => $user->getOriginal('payment_status'),
                    'new_payment_status' => $user->payment_status,
                    'external_id' => $payload['external_id']
                ]);
            }

            // If this was a collective payment and it's paid, send confirmation to all participants
            if (count($processedUsers) > 1 && strtolower($payload['status']) === 'paid') {
                $this->sendCollectivePaymentConfirmation($processedUsers, $payload);
            }

            return $processedUsers;
        } catch (\Exception $e) {
            Log::error('Webhook processing error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'payload' => $payload
            ]);
            return false;
        }
    }

    /**
     * Send collective payment confirmation to all participants
     */
    private function sendCollectivePaymentConfirmation($users, $payload)
    {
        try {
            foreach ($users as $user) {
                $message = "🎉 *PEMBAYARAN BERHASIL!* 🎉\n\n";
                $message .= "Halo *{$user->name}*!\n\n";
                $message .= "Pembayaran kolektif untuk Amazing Sultra Run telah berhasil dikonfirmasi!\n\n";
                $message .= "📋 *Detail Registrasi:*\n";
                $message .= "• Nama: {$user->name}\n";
                $message .= "• Kategori: {$user->race_category}\n";
                $message .= "• Jersey: {$user->jersey_size}\n";
                $message .= "• Status: TERDAFTAR ✅\n\n";
                $message .= "🎽 Jersey akan dikirim ke alamat grup leader sebelum hari event.\n\n";
                $message .= "📱 Untuk informasi lebih lanjut, silakan login ke:\n";
                $message .= "🔗 " . url('/login') . "\n\n";
                $message .= "Terima kasih dan selamat berlari! 🏃‍♂️🏃‍♀️\n\n";
                $message .= "_Tim Amazing Sultra Run_";

                // Use WhatsApp service to send message
                try {
                    app('App\Services\WhatsAppService')->sendMessage($user->whatsapp_number, $message);
                    
                    Log::info('Collective payment confirmation sent', [
                        'user_id' => $user->id,
                        'user_name' => $user->name,
                        'whatsapp' => $user->whatsapp_number
                    ]);
                } catch (\Exception $e) {
                    Log::error('Failed to send collective payment confirmation', [
                        'user_id' => $user->id,
                        'error' => $e->getMessage()
                    ]);
                }
            }
        } catch (\Exception $e) {
            Log::error('Exception in sendCollectivePaymentConfirmation', [
                'error' => $e->getMessage(),
                'user_count' => count($users)
            ]);
        }
    }

    /**
     * SECURITY: Validate and get official price from database
     * This prevents price manipulation attacks
     */
    public function validateAndGetOfficialPrice(User $user): float
    {
        try {
            // SECURITY: Always get price from ticket_types table (source of truth)
            // Never trust user input or stored user.registration_fee for price validation
            
            $ticketType = null;
            
            // First priority: Use ticket_type_id if available (works for all registration types including wakaf)
            if ($user->ticket_type_id) {
                $ticketType = \App\Models\TicketType::find($user->ticket_type_id);
                \Log::info('Attempting to get ticket type by ID', [
                    'user_id' => $user->id,
                    'ticket_type_id' => $user->ticket_type_id,
                    'found' => !is_null($ticketType)
                ]);
            }
            
            // Fallback: Use getCurrentTicketType method (for backward compatibility)
            if (!$ticketType && $user->race_category) {
                $ticketType = \App\Models\TicketType::getCurrentTicketType($user->race_category);
                \Log::info('Fallback to getCurrentTicketType', [
                    'user_id' => $user->id,
                    'race_category' => $user->race_category,
                    'found' => !is_null($ticketType)
                ]);
            }
            
            if (!$ticketType) {
                throw new \Exception('No valid ticket type found for user ID: ' . $user->id . ', category: ' . $user->race_category . ', ticket_type_id: ' . $user->ticket_type_id);
            }
            
            // For special ticket types like 'wakaf', skip active/quota validation
            if (!in_array($ticketType->name, ['wakaf']) && !$ticketType->isCurrentlyActive()) {
                throw new \Exception('Ticket type is no longer active or quota exceeded');
            }
            
            $officialPrice = (float) $ticketType->price;
            
            // Additional sanity checks
            if ($officialPrice <= 0) {
                throw new \Exception('Invalid ticket price: ' . $officialPrice);
            }
            
            // Security check: If user has stored registration_fee, verify it matches current price
            if ($user->registration_fee && $user->registration_fee > 0) {
                if ($user->registration_fee !== $officialPrice) {
                    \Log::warning('User stored registration_fee does not match current ticket price', [
                        'user_id' => $user->id,
                        'category' => $user->race_category,
                        'stored_fee' => $user->registration_fee,
                        'current_price' => $officialPrice,
                        'ticket_type_id' => $ticketType->id
                    ]);
                    
                    // For security, always use current ticket price from database
                    // This prevents price manipulation via stored user data
                }
            }
            
            \Log::info('Official price validated from ticket_types database', [
                'user_id' => $user->id,
                'user_ticket_type_id' => $user->ticket_type_id,
                'category' => $user->race_category,
                'ticket_type_id' => $ticketType->id,
                'ticket_type_name' => $ticketType->name,
                'official_price' => $officialPrice,
                'source' => 'ticket_types.price',
                'method' => $user->ticket_type_id ? 'direct_ticket_type_id' : 'getCurrentTicketType_fallback'
            ]);
            
            return $officialPrice;
            
        } catch (\Exception $e) {
            \Log::error('Price validation failed', [
                'user_id' => $user->id,
                'user_ticket_type_id' => $user->ticket_type_id,
                'category' => $user->race_category,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }
    
    /**
     * Get official collective price for a specific race category
     * Used for collective registration pricing validation
     */
    public function getCollectivePrice(string $raceCategory)
    {
        try {
            // Get collective ticket type for the race category
            // Priority: First look for tickets with "kolektif" in the name, then fallback to any active ticket
            $ticketType = \App\Models\TicketType::where('is_active', true)
                ->whereHas('raceCategory', function($query) use ($raceCategory) {
                    $query->where('name', $raceCategory);
                })
                ->where(function($query) {
                    $query->where('name', 'like', '%kolektif%')
                          ->orWhere('name', 'like', '%Kolektif%')
                          ->orWhere('name', 'like', '%collective%')
                          ->orWhere('name', 'like', '%Collective%');
                })
                ->first();
            
            // If no specific collective ticket found, use any active ticket for the category
            if (!$ticketType) {
                $ticketType = \App\Models\TicketType::where('is_active', true)
                    ->whereHas('raceCategory', function($query) use ($raceCategory) {
                        $query->where('name', $raceCategory);
                    })
                    ->first();
            }
            
            if (!$ticketType) {
                \Log::warning('No valid ticket type found for collective registration', [
                    'race_category' => $raceCategory
                ]);
                return false;
            }
            
            // For collective registration, use the ticket price
            $collectivePrice = (float) $ticketType->price;
            
            if ($collectivePrice <= 0) {
                \Log::warning('Invalid collective price detected', [
                    'race_category' => $raceCategory,
                    'price' => $collectivePrice,
                    'ticket_type_id' => $ticketType->id,
                    'ticket_name' => $ticketType->name
                ]);
                return false;
            }
            
            \Log::info('Collective price validated', [
                'race_category' => $raceCategory,
                'price' => $collectivePrice,
                'ticket_type_id' => $ticketType->id,
                'ticket_name' => $ticketType->name
            ]);
            
            return $collectivePrice;
            
        } catch (\Exception $e) {
            \Log::error('Collective price validation failed', [
                'race_category' => $raceCategory,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Create invoice for collective registration payment
     * SECURITY: Price is validated for each participant beforehand
     */
    public function createCollectiveInvoice(User $primaryUser, array $participants, float $totalAmount, string $description = 'Amazing Sultra Run - Collective Registration')
    {
        // SECURITY: Validate configuration
        if (empty($this->apiKey)) {
            throw new \Exception('Xendit API key is not configured');
        }

        // SECURITY: Validate total amount
        if ($totalAmount <= 0 || $totalAmount > 50000000) { // Max 50 million IDR for collective
            \Log::critical('SECURITY ALERT: Invalid collective amount detected', [
                'primary_user_id' => $primaryUser->id,
                'total_amount' => $totalAmount,
                'participant_count' => count($participants),
                'action' => 'transaction_blocked'
            ]);
            throw new \Exception('Security violation: Invalid collective amount detected.');
        }

        // SECURITY: Validate participant count
        if (count($participants) < 10 || count($participants) > 100) { // Min 10, Max 100 participants
            \Log::warning('Invalid participant count for collective registration', [
                'primary_user_id' => $primaryUser->id,
                'participant_count' => count($participants)
            ]);
            throw new \Exception('Invalid participant count for collective registration.');
        }

        try {
            $externalId = 'AMAZING-COLLECTIVE-' . $primaryUser->id . '-' . time();
            
            // Build items array for each participant
            $items = [];
            foreach ($participants as $participant) {
                $items[] = [
                    'name' => 'Registration: ' . $participant['name'] . ' (' . $participant['category'] . ')',
                    'quantity' => 1,
                    'price' => $participant['fee'],
                    'category' => 'Registration Fee'
                ];
            }

            $payload = [
                'external_id' => $externalId,
                'amount' => $totalAmount,
                'description' => $description,
                'invoice_duration' => 86400, // 24 hours
                'customer' => [
                    'given_names' => $primaryUser->name . ' (Group Leader)',
                    'email' => $primaryUser->email,
                    'mobile_number' => $primaryUser->phone ?: $primaryUser->whatsapp_number,
                ],
                'customer_notification_preference' => [
                    'invoice_created' => ['whatsapp', 'sms', 'email'],
                    'invoice_reminder' => ['whatsapp', 'sms', 'email'],
                    'invoice_paid' => ['whatsapp', 'sms', 'email'],
                    'invoice_expired' => ['whatsapp', 'sms', 'email']
                ],
                'success_redirect_url' => url('/payment/success'),
                'failure_redirect_url' => url('/payment/failed'),
                'currency' => 'IDR',
                'items' => $items
            ];

            \Log::info('Creating Xendit collective invoice', [
                'primary_user_id' => $primaryUser->id,
                'external_id' => $externalId,
                'total_amount' => $totalAmount,
                'participant_count' => count($participants),
                'payload' => $payload
            ]);

            $response = Http::withBasicAuth($this->apiKey, '')
                ->post($this->baseUrl . '/v2/invoices', $payload);

            if ($response->successful()) {
                $invoiceData = $response->json();
                
                \Log::info('Xendit collective invoice created successfully', [
                    'primary_user_id' => $primaryUser->id,
                    'external_id' => $externalId,
                    'invoice_id' => $invoiceData['id'] ?? null,
                    'invoice_url' => $invoiceData['invoice_url'] ?? null,
                    'total_amount' => $totalAmount,
                    'participant_count' => count($participants)
                ]);

                return [
                    'success' => true,
                    'invoice_id' => $invoiceData['id'] ?? null,
                    'invoice_url' => $invoiceData['invoice_url'] ?? null,
                    'external_id' => $externalId,
                    'amount' => $totalAmount,
                    'data' => $invoiceData
                ];
            } else {
                $errorMessage = $response->json()['message'] ?? 'Unknown error from Xendit';
                
                \Log::error('Failed to create Xendit collective invoice', [
                    'primary_user_id' => $primaryUser->id,
                    'external_id' => $externalId,
                    'error' => $errorMessage,
                    'status_code' => $response->status(),
                    'response_body' => $response->body()
                ]);

                return [
                    'success' => false,
                    'message' => $errorMessage
                ];
            }

        } catch (\Exception $e) {
            \Log::error('Exception creating Xendit collective invoice', [
                'primary_user_id' => $primaryUser->id,
                'total_amount' => $totalAmount,
                'participant_count' => count($participants),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'message' => 'Failed to create collective payment invoice: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Create collective invoice for admin import (bypass minimum participant validation)
     * 
     * @param User $primaryUser
     * @param array $participants Array of User models
     * @param string $groupName
     * @return string Invoice URL
     */
    public function createCollectiveInvoiceForAdmin($primaryUser, $participants, $groupName = 'Admin Import Group')
    {
        \Log::info('Creating collective invoice for admin import', [
            'primary_user_id' => $primaryUser->id,
            'participant_count' => count($participants),
            'group_name' => $groupName
        ]);

        // ADMIN: No minimum participant validation - allow any number >= 1
        if (count($participants) < 1 || count($participants) > 100) {
            \Log::warning('Invalid participant count for admin collective registration', [
                'primary_user_id' => $primaryUser->id,
                'participant_count' => count($participants)
            ]);
            throw new \Exception('Invalid participant count for admin collective registration. Maximum 100 participants allowed.');
        }

        try {
            // Use existing external ID if all participants have the same one (collective import)
            // Otherwise generate new one
            $existingExternalIds = array_unique(array_filter(array_map(function($user) {
                return $user->xendit_external_id;
            }, $participants)));
            
            if (count($existingExternalIds) === 1 && strpos($existingExternalIds[0], 'AMAZING-ADMIN-COLLECTIVE-') === 0) {
                // Use existing collective external ID from CSV import
                $externalId = $existingExternalIds[0];
                \Log::info('Using existing collective external ID for admin import', [
                    'external_id' => $externalId,
                    'participant_count' => count($participants)
                ]);
            } else {
                // Generate new external ID for manual collective creation
                $externalId = 'AMAZING-ADMIN-COLLECTIVE-' . $primaryUser->id . '-' . time();
                \Log::info('Generated new collective external ID for admin', [
                    'external_id' => $externalId,
                    'participant_count' => count($participants)
                ]);
            }
            
            // Calculate total amount and prepare participant details
            $totalAmount = 0;
            $participantDetails = [];
            
            foreach ($participants as $user) {
                // SECURITY: Use official price validation method for consistency
                $price = $this->validateAndGetOfficialPrice($user);
                
                $totalAmount += $price;
                $participantDetails[] = [
                    'name' => $user->name,
                    'email' => $user->email,
                    'category' => $user->race_category,
                    'fee' => $price
                ];
            }

            // Create invoice
            $params = [
                'external_id' => $externalId,
                'payer_email' => $primaryUser->email,
                'description' => "Collective Registration - {$groupName} (" . count($participants) . " participants)",
                'amount' => $totalAmount,
                'invoice_duration' => 86400, // 24 hours
                'success_redirect_url' => url('/payment/success'),
                'failure_redirect_url' => url('/payment/failed'),
                'should_send_email' => true,
                'customer' => [
                    'given_names' => $primaryUser->name,
                    'email' => $primaryUser->email,
                    'mobile_number' => $primaryUser->whatsapp_number,
                ],
                'customer_notification_preference' => [
                    'invoice_created' => ['whatsapp', 'email'],
                    'invoice_reminder' => ['whatsapp', 'email'],
                    'invoice_paid' => ['whatsapp', 'email'],
                    'invoice_expired' => ['whatsapp', 'email']
                ],
                'items' => []
            ];

            // Add items for each participant
            foreach ($participantDetails as $participant) {
                $params['items'][] = [
                    'name' => "Registration Fee - {$participant['name']} ({$participant['category']})",
                    'quantity' => 1,
                    'price' => $participant['fee'],
                    'category' => $participant['category']
                ];
            }

            // Create invoice using HTTP client (consistent with rest of the service)
            $response = Http::withBasicAuth($this->apiKey, '')
                ->withHeaders([
                    'Content-Type' => 'application/json'
                ])
                ->post($this->baseUrl . '/v2/invoices', $params);

            if (!$response->successful()) {
                throw new \Exception('Failed to create invoice: ' . $response->body());
            }

            $invoice = $response->json();

            // Update all participants with invoice details
            foreach ($participants as $user) {
                $updateData = [
                    'xendit_invoice_id' => $invoice['id'],
                    'xendit_invoice_url' => $invoice['invoice_url'],
                    'payment_status' => 'pending'
                ];
                
                // Only update external ID if it's different (to preserve CSV import collective IDs)
                if ($user->xendit_external_id !== $externalId) {
                    $updateData['xendit_external_id'] = $externalId;
                }
                
                $user->update($updateData);
            }

            \Log::info('Admin collective invoice created successfully', [
                'invoice_id' => $invoice['id'],
                'external_id' => $externalId,
                'amount' => $totalAmount,
                'participant_count' => count($participants),
                'group_name' => $groupName
            ]);

            return $invoice['invoice_url'];
            
        } catch (\Exception $e) {
            \Log::error('Failed to create admin collective invoice', [
                'error' => $e->getMessage(),
                'primary_user_id' => $primaryUser->id,
                'participant_count' => count($participants),
                'group_name' => $groupName
            ]);
            
            throw new \Exception('Failed to create collective invoice: ' . $e->getMessage());
        }
    }
}
