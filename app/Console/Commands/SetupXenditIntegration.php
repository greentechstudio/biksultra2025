<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\XenditService;
use App\Models\User;
use Illuminate\Support\Facades\Schema;

class SetupXenditIntegration extends Command
{
    protected $signature = 'xendit:setup {--test : Run integration tests}';
    protected $description = 'Setup and test Xendit payment integration';

    public function handle()
    {
        $this->info('🚀 Setting up Xendit Integration...');
        $this->newLine();

        // Check environment variables
        $this->checkEnvironmentVariables();

        // Run migrations
        $this->runMigrations();

        // Check database schema
        $this->checkDatabaseSchema();

        if ($this->option('test')) {
            $this->runIntegrationTests();
        }

        $this->displayNextSteps();
    }

    private function checkEnvironmentVariables()
    {
        $this->info('1. Checking environment variables...');
        
        $requiredVars = [
            'XENDIT_SECRET_KEY' => 'Your Xendit secret key',
            'XENDIT_WEBHOOK_TOKEN' => 'Your Xendit webhook verification token',
            'REGISTRATION_FEE' => 'Registration fee amount (default: 150000)',
            'APP_URL' => 'Your application URL for webhook'
        ];

        $missing = [];
        
        foreach ($requiredVars as $var => $description) {
            if (empty(env($var))) {
                $missing[] = $var;
                $this->error("   ❌ {$var} - {$description}");
            } else {
                $this->line("   ✅ {$var}");
            }
        }

        if (!empty($missing)) {
            $this->newLine();
            $this->error('Please add the missing environment variables to your .env file:');
            $this->newLine();
            foreach ($missing as $var) {
                $this->line("# {$requiredVars[$var]}");
                $this->line("{$var}=your_value_here");
            }
            $this->newLine();
            exit(1);
        }

        $this->info('   All environment variables are configured!');
        $this->newLine();
    }

    private function runMigrations()
    {
        $this->info('2. Running database migrations...');
        
        try {
            $this->call('migrate', ['--force' => true]);
            $this->info('   ✅ Migrations completed successfully!');
        } catch (\Exception $e) {
            $this->error('   ❌ Migration failed: ' . $e->getMessage());
            exit(1);
        }
        
        $this->newLine();
    }

    private function checkDatabaseSchema()
    {
        $this->info('3. Checking database schema...');
        
        $requiredFields = [
            'xendit_invoice_id',
            'xendit_invoice_url', 
            'xendit_external_id',
            'payment_status',
            'payment_requested_at',
            'xendit_callback_data'
        ];

        try {
            $schema = Schema::getColumnListing('users');
            $missing = array_diff($requiredFields, $schema);
            
            if (empty($missing)) {
                $this->info('   ✅ All required database fields exist!');
            } else {
                $this->error('   ❌ Missing database fields: ' . implode(', ', $missing));
                $this->error('   Please run the migration again or check your database.');
                exit(1);
            }
        } catch (\Exception $e) {
            $this->error('   ❌ Database schema check failed: ' . $e->getMessage());
            exit(1);
        }
        
        $this->newLine();
    }

    private function runIntegrationTests()
    {
        $this->info('4. Running integration tests...');
        
        // Test 1: Create test invoice
        $this->testInvoiceCreation();
        
        // Test 2: Test webhook verification
        $this->testWebhookVerification();
        
        $this->newLine();
    }

    private function testInvoiceCreation()
    {
        $this->line('   Testing invoice creation...');
        
        try {
            // Create or find test user
            $testUser = User::firstOrCreate(
                ['email' => 'test.xendit@example.com'],
                [
                    'name' => 'Test Xendit User',
                    'phone' => '628123456789',
                    'whatsapp_number' => '628123456789',
                    'password' => bcrypt('password123'),
                    'role' => 'user',
                    'status' => 'pending',
                    'gender' => 'Laki-laki',
                    'birth_place' => 'Jakarta',
                    'birth_date' => '1990-01-01',
                    'address' => 'Test Address',
                    'jersey_size' => 'L',
                    'race_category' => '10K',
                    'emergency_contact_1' => 'Emergency Contact',
                    'blood_type' => 'O',
                    'occupation' => 'Tester',
                    'event_source' => 'Test'
                ]
            );

            $xenditService = app(XenditService::class);
            $result = $xenditService->createInvoice($testUser, 150000, 'Test Registration Fee');
            
            if ($result['success']) {
                $this->info('   ✅ Invoice creation test passed!');
                $this->line("      Invoice ID: {$testUser->fresh()->xendit_invoice_id}");
                $this->line("      External ID: {$testUser->fresh()->xendit_external_id}");
            } else {
                $this->error('   ❌ Invoice creation test failed: ' . $result['error']);
            }
        } catch (\Exception $e) {
            $this->error('   ❌ Invoice creation test exception: ' . $e->getMessage());
        }
    }

    private function testWebhookVerification()
    {
        $this->line('   Testing webhook verification...');
        
        try {
            $xenditService = app(XenditService::class);
            $testPayload = json_encode(['test' => 'data']);
            $testSignature = hash_hmac('sha256', $testPayload, env('XENDIT_WEBHOOK_TOKEN'));
            
            $isValid = $xenditService->verifyWebhookSignature($testPayload, $testSignature);
            
            if ($isValid) {
                $this->info('   ✅ Webhook verification test passed!');
            } else {
                $this->error('   ❌ Webhook verification test failed!');
            }
        } catch (\Exception $e) {
            $this->error('   ❌ Webhook verification test exception: ' . $e->getMessage());
        }
    }

    private function displayNextSteps()
    {
        $this->info('🎉 Xendit integration setup completed!');
        $this->newLine();
        
        $this->info('Next steps:');
        $this->line('1. Configure webhook URL in your Xendit dashboard:');
        $this->line('   ' . env('APP_URL') . '/api/xendit/webhook');
        $this->newLine();
        
        $this->line('2. Test the registration flow:');
        $this->line('   - Go to /register');
        $this->line('   - Complete registration form');
        $this->line('   - Check WhatsApp for payment link');
        $this->newLine();
        
        $this->line('3. Monitor logs:');
        $this->line('   - tail -f storage/logs/laravel.log');
        $this->newLine();
        
        $this->line('4. Test webhook endpoint:');
        $this->line('   curl -X POST ' . env('APP_URL') . '/api/xendit/webhook \\');
        $this->line('     -H "Content-Type: application/json" \\');
        $this->line('     -H "x-callback-token: ' . env('XENDIT_WEBHOOK_TOKEN', 'your_token') . '" \\');
        $this->line('     -d \'{"external_id":"test-external-id","status":"PAID"}\'');
        $this->newLine();
    }
}
