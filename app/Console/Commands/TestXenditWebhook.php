<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Services\XenditService;
use Illuminate\Support\Facades\Http;

class TestXenditWebhook extends Command
{
    protected $signature = 'xendit:test-webhook {--user-id= : User ID to test} {--external-id= : External ID to test}';
    protected $description = 'Test Xendit webhook functionality';

    public function handle()
    {
        $this->info('🔍 Testing Xendit Webhook...');
        $this->newLine();
        
        // Get user for testing
        $user = $this->getTestUser();
        
        if (!$user) {
            $this->error('No user found for testing');
            return;
        }
        
        $this->info("Testing with user: {$user->name} (ID: {$user->id})");
        $this->info("External ID: {$user->xendit_external_id}");
        $this->info("Current Status: {$user->status}");
        $this->info("Current Payment Status: {$user->payment_status}");
        $this->newLine();
        
        // Test webhook payload
        $testPayload = [
            'id' => 'test-invoice-' . time(),
            'external_id' => $user->xendit_external_id,
            'status' => 'PAID',
            'amount' => 150000,
            'payment_method' => 'BCA',
            'paid_at' => now()->toISOString(),
            'payment_channel' => 'BANK_TRANSFER',
            'payment_destination' => '8234567890'
        ];
        
        $this->info('Sending test webhook payload...');
        $this->line(json_encode($testPayload, JSON_PRETTY_PRINT));
        $this->newLine();
        
        // Test webhook URL
        $webhookUrl = url('/api/xendit/webhook');
        
        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'x-callback-token' => config('xendit.webhook_token')
            ])->post($webhookUrl, $testPayload);
            
            $this->info('Webhook Response:');
            $this->line("Status: {$response->status()}");
            $this->line("Body: {$response->body()}");
            $this->newLine();
            
            // Check user status after webhook
            $user->refresh();
            $this->info('User status after webhook:');
            $this->line("Status: {$user->status}");
            $this->line("Payment Status: {$user->payment_status}");
            $this->line("Payment Confirmed: " . ($user->payment_confirmed ? 'Yes' : 'No'));
            $this->line("Payment Confirmed At: " . ($user->payment_confirmed_at ?? 'Not set'));
            
            if ($user->payment_confirmed && $user->status === 'active') {
                $this->info('✅ Webhook test PASSED! User status updated correctly.');
            } else {
                $this->error('❌ Webhook test FAILED! User status not updated properly.');
            }
            
        } catch (\Exception $e) {
            $this->error('❌ Webhook test failed: ' . $e->getMessage());
        }
    }
    
    private function getTestUser()
    {
        if ($this->option('user-id')) {
            return User::find($this->option('user-id'));
        }
        
        if ($this->option('external-id')) {
            return User::where('xendit_external_id', $this->option('external-id'))->first();
        }
        
        // Find a user with pending payment
        return User::where('payment_status', 'pending')
                   ->whereNotNull('xendit_external_id')
                   ->first();
    }
}
