<?php

namespace App\Console\Commands;

use App\Jobs\CleanupUnpaidRegistrations;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CleanupUnpaidUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cleanup:unpaid-users {--dry-run : Show what would be deleted without actually deleting}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cleanup users who have not paid after 24 hours';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if ($this->option('dry-run')) {
            $this->info('🔍 Running dry run - showing what would be deleted...');
            
            $cutoffTime = now()->subHours(24);
            
            $unpaidUsers = \App\Models\User::where('created_at', '<', $cutoffTime)
                ->where('payment_status', '!=', 'paid')
                ->whereNull('payment_confirmed_at')
                ->get();
            
            if ($unpaidUsers->isEmpty()) {
                $this->info('✅ No users found for cleanup');
                return 0;
            }
            
            $this->info("📋 Found {$unpaidUsers->count()} users to cleanup:");
            $this->newLine();
            
            $this->table(
                ['ID', 'Name', 'Email', 'Race Category', 'Registered At', 'Hours Ago'],
                $unpaidUsers->map(function ($user) {
                    return [
                        $user->id,
                        $user->name,
                        $user->email,
                        $user->race_category,
                        $user->created_at->format('Y-m-d H:i:s'),
                        $user->created_at->diffInHours(now())
                    ];
                })
            );
            
            $this->newLine();
            $this->warn('⚠️  To actually delete these users, run: php artisan cleanup:unpaid-users');
            
        } else {
            $this->info('🧹 Starting cleanup of unpaid users after 24 hours...');
            
            try {
                CleanupUnpaidRegistrations::dispatch();
                $this->info('✅ Cleanup job dispatched successfully');
                
                Log::info('Manual cleanup job dispatched via artisan command');
                
            } catch (\Exception $e) {
                $this->error("❌ Error dispatching cleanup job: {$e->getMessage()}");
                Log::error('Error dispatching cleanup job', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                return 1;
            }
        }
        
        return 0;
    }
}
