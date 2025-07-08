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
     */
    public function createInvoice(User $user, $amount = null, $description = 'Amazing Sultra Run Registration Fee')
    {
        try {
            // Additional validation
            if (empty($this->apiKey)) {
                throw new \Exception('Xendit API key is not configured');
            }
            
            // Get amount from user's race category if not provided
            if ($amount === null) {
                $amount = $user->registration_fee;
            }
            
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

            // Find user by external_id
            $user = User::where('xendit_external_id', $payload['external_id'])->first();
            
            if (!$user) {
                Log::warning('User not found for external_id', ['external_id' => $payload['external_id']]);
                return false;
            }

            Log::info('User found for webhook processing', [
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

            Log::info('User payment status updated successfully', [
                'user_id' => $user->id,
                'old_status' => $user->getOriginal('status'),
                'new_status' => $user->status,
                'old_payment_status' => $user->getOriginal('payment_status'),
                'new_payment_status' => $user->payment_status,
                'external_id' => $payload['external_id']
            ]);

            return $user;
        } catch (\Exception $e) {
            Log::error('Webhook processing error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'payload' => $payload
            ]);
            return false;
        }
    }
}
