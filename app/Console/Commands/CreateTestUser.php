<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateTestUser extends Command
{
    protected $signature = 'user:create-test {--external-id= : Custom external ID for the user}';
    protected $description = 'Create a test user for webhook testing';

    public function handle()
    {
        $this->info('Creating test user for webhook testing...');
        
        $externalId = $this->option('external-id') ?: 'AMAZING-REG-TEST-' . time();
        $email = 'test.webhook.' . time() . '@example.com';
        
        $user = User::create([
            'name' => 'Test User Webhook',
            'email' => $email,
            'password' => Hash::make('password'),
            'phone' => '628123456789',
            'whatsapp_number' => '628123456789',
            'status' => 'pending',
            'payment_status' => 'pending',
            'gender' => 'Laki-laki',
            'birth_place' => 'Jakarta',
            'birth_date' => '1990-01-01',
            'address' => 'Test Address',
            'jersey_size' => 'L',
            'race_category' => '10K',
            'emergency_contact_1' => 'Emergency',
            'blood_type' => 'O',
            'occupation' => 'Tester',
            'event_source' => 'Test',
            'xendit_external_id' => $externalId,
            'xendit_invoice_id' => 'test-invoice-' . time(),
            'payment_requested_at' => now()
        ]);
        
        $this->info('Test user created successfully!');
        $this->line('User ID: ' . $user->id);
        $this->line('Name: ' . $user->name);
        $this->line('Email: ' . $user->email);
        $this->line('External ID: ' . $user->xendit_external_id);
        $this->line('Status: ' . $user->status);
        $this->line('Payment Status: ' . $user->payment_status);
        
        return 0;
    }
}
