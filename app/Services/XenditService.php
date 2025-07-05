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
    }

    /**
     * Create invoice for user registration payment
     */
    public function createInvoice(User $user, $amount = null, $description = 'Amazing Sultra Run Registration Fee')
    {
        try {
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
        $expectedSignature = hash_hmac('sha256', $rawBody, $this->webhookToken);
        return hash_equals($expectedSignature, $signature);
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

            // Update user payment status
            $updateData = [
                'xendit_callback_data' => $payload,
                'payment_status' => strtolower($payload['status'])
            ];

            if (strtolower($payload['status']) === 'paid') {
                $updateData['payment_confirmed'] = true;
                $updateData['payment_confirmed_at'] = now();
                $updateData['payment_method'] = $payload['payment_method'] ?? 'xendit';
                $updateData['status'] = 'active';
            }

            $user->update($updateData);

            Log::info('User payment status updated', [
                'user_id' => $user->id,
                'status' => $payload['status'],
                'external_id' => $payload['external_id']
            ]);

            return $user;
        } catch (\Exception $e) {
            Log::error('Webhook processing error', [
                'error' => $e->getMessage(),
                'payload' => $payload
            ]);
            return false;
        }
    }
}
