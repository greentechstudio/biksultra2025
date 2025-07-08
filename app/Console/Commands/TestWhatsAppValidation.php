<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\AuthController;
use ReflectionClass;
use ReflectionMethod;

class TestWhatsAppValidation extends Command
{
    protected $signature = 'test:whatsapp-validation {number}';
    protected $description = 'Test WhatsApp number validation';

    public function handle()
    {
        $number = $this->argument('number');
        
        $this->info("🔧 Testing WhatsApp validation for: {$number}");
        
        // Create instance of AuthController
        $authController = app(AuthController::class);
        
        // Use reflection to call private method
        $reflection = new ReflectionClass($authController);
        $method = $reflection->getMethod('validateWhatsAppNumber');
        $method->setAccessible(true);
        
        // Format phone number first
        $formatMethod = $reflection->getMethod('formatPhoneNumber');
        $formatMethod->setAccessible(true);
        $formattedNumber = $formatMethod->invoke($authController, $number);
        
        $this->line("📱 Formatted number: {$formattedNumber}");
        
        // Test validation
        $result = $method->invoke($authController, $formattedNumber);
        
        $this->line("📊 Validation result:");
        $this->line("  Success: " . ($result['success'] ? 'Yes' : 'No'));
        $this->line("  Valid: " . ($result['valid'] ? 'Yes' : 'No'));
        $this->line("  Message: " . $result['message']);
        
        if ($result['success'] && $result['valid']) {
            $this->info("✅ WhatsApp number is valid!");
        } elseif ($result['success'] && !$result['valid']) {
            $this->warn("⚠️  WhatsApp number is not registered");
        } else {
            $this->error("❌ Validation failed: " . $result['message']);
        }
    }
}
