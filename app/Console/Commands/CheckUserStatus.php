<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class CheckUserStatus extends Command
{
    protected $signature = 'user:status {user_id}';
    protected $description = 'Check user payment status';

    public function handle()
    {
        $userId = $this->argument('user_id');
        $user = User::find($userId);
        
        if (!$user) {
            $this->error('User not found');
            return;
        }
        
        $this->info("User Status Check for ID: {$userId}");
        $this->line("Name: {$user->name}");
        $this->line("Email: {$user->email}");
        $this->line("Status: {$user->status}");
        $this->line("Payment Status: {$user->payment_status}");
        $this->line("Payment Confirmed: " . ($user->payment_confirmed ? 'Yes' : 'No'));
        $this->line("Payment Confirmed At: " . ($user->payment_confirmed_at ?? 'Not set'));
        $this->line("External ID: {$user->xendit_external_id}");
        $this->line("Invoice ID: {$user->xendit_invoice_id}");
        
        return 0;
    }
}
