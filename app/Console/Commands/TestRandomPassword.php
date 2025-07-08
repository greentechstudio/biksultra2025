<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Services\RandomPasswordService;
use App\Services\WhatsAppService;

class TestRandomPassword extends Command
{
    protected $signature = 'test:random-password {user_id} {--type=simple}';
    protected $description = 'Test random password generation for a user';

    public function handle()
    {
        $userId = $this->argument('user_id');
        $type = $this->option('type');
        
        $this->info("🔧 Testing random password generation...");
        
        // Find user
        $user = User::find($userId);
        if (!$user) {
            $this->error("❌ User not found with ID: {$userId}");
            return;
        }
        
        $this->info("✅ User found: {$user->name} ({$user->email})");
        
        // Test password generation
        $randomPasswordService = new RandomPasswordService();
        $whatsappService = new WhatsAppService();
        
        $this->info("🔑 Generating {$type} password...");
        
        $result = $randomPasswordService->generateAndSendPassword($user, $whatsappService, $type);
        
        if ($result['success']) {
            $this->info("✅ Password generated successfully!");
            $this->line("Password sent to WhatsApp: " . ($result['password_sent'] ? 'Yes' : 'No'));
            
            if (!$result['password_sent'] && isset($result['password'])) {
                $this->warn("⚠️  Password (not sent via WhatsApp): {$result['password']}");
            }
            
            $this->info("Message: {$result['message']}");
        } else {
            $this->error("❌ Failed to generate password: {$result['message']}");
        }
    }
}
