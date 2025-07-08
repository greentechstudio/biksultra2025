<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\WhatsAppService;
use App\Services\RandomPasswordService;
use App\Models\User;

class TestWhatsAppRegistration extends Command
{
    protected $signature = 'test:whatsapp-registration';
    protected $description = 'Test WhatsApp functionality during registration';

    public function handle()
    {
        $this->info('Testing WhatsApp Registration Flow...');
        $this->newLine();

        // Test 1: WhatsApp Service
        $this->info('1. Testing WhatsApp Service...');
        try {
            $whatsappService = app(WhatsAppService::class);
            $result = $whatsappService->sendMessage('628114000805', 'Test message from registration flow');
            
            if ($result) {
                $this->info('✅ WhatsApp Service: SUCCESS');
            } else {
                $this->error('❌ WhatsApp Service: FAILED');
            }
        } catch (\Exception $e) {
            $this->error('❌ WhatsApp Service Exception: ' . $e->getMessage());
        }
        $this->newLine();

        // Test 2: RandomPassword Service
        $this->info('2. Testing RandomPassword Service...');
        try {
            $user = User::first();
            if ($user) {
                $passwordService = new RandomPasswordService();
                $whatsappService = app(WhatsAppService::class);
                $result = $passwordService->generateAndSendPassword($user, $whatsappService, 'simple');
                
                if ($result['success']) {
                    $this->info('✅ Password Service: SUCCESS');
                    $this->info('   Password sent: ' . ($result['password_sent'] ? 'YES' : 'NO'));
                } else {
                    $this->error('❌ Password Service: FAILED - ' . $result['message']);
                }
            } else {
                $this->error('❌ No users found in database');
            }
        } catch (\Exception $e) {
            $this->error('❌ Password Service Exception: ' . $e->getMessage());
        }
        $this->newLine();

        // Test 3: Configuration
        $this->info('3. Checking Configuration...');
        $this->info('   WHATSAPP_API_KEY: ' . (env('WHATSAPP_API_KEY') ? 'SET' : 'NOT SET'));
        $this->info('   WHATSAPP_SENDER: ' . (env('WHATSAPP_SENDER') ? env('WHATSAPP_SENDER') : 'NOT SET'));
        $this->info('   API URL: https://wamd.system112.org/send-message');
        $this->newLine();

        // Test 4: Check recent logs
        $this->info('4. Recent WhatsApp logs:');
        $logPath = storage_path('logs/laravel.log');
        if (file_exists($logPath)) {
            $lines = file($logPath);
            $recent = array_slice($lines, -20);
            $whatsappLogs = array_filter($recent, function($line) {
                return strpos($line, 'WhatsApp') !== false;
            });
            
            if (count($whatsappLogs) > 0) {
                foreach (array_slice($whatsappLogs, -5) as $log) {
                    $this->line('   ' . trim($log));
                }
            } else {
                $this->info('   No recent WhatsApp logs found');
            }
        } else {
            $this->error('   No log file found');
        }

        $this->newLine();
        $this->info('Test completed!');
    }
}
