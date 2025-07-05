<?php

namespace App\Console\Commands;

use App\Jobs\CleanupUnpaidRegistrations;
use App\Jobs\SendPaymentReminders;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ProcessUnpaidRegistrations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'registrations:process-unpaid 
                           {--cleanup : Only run cleanup (delete unpaid users)}
                           {--reminders : Only send reminders}
                           {--dry-run : Show what would be done without executing}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process unpaid registrations: send reminders and cleanup expired ones';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('🏃‍♂️ Amazing Sultra Run - Processing Unpaid Registrations');
        $this->newLine();

        $onlyCleanup = $this->option('cleanup');
        $onlyReminders = $this->option('reminders');
        $dryRun = $this->option('dry-run');

        if ($dryRun) {
            $this->warn('⚠️  DRY RUN MODE - No actual actions will be performed');
            $this->newLine();
        }

        // Send payment reminders (unless cleanup-only mode)
        if (!$onlyCleanup) {
            $this->info('📱 Processing payment reminders...');
            
            if (!$dryRun) {
                SendPaymentReminders::dispatch();
                $this->info('✅ Payment reminder job dispatched');
            } else {
                $this->info('🔍 Would dispatch payment reminder job');
            }
            
            $this->newLine();
        }

        // Cleanup unpaid registrations (unless reminders-only mode)
        if (!$onlyReminders) {
            $this->info('🗑️  Processing cleanup of expired registrations...');
            
            if (!$dryRun) {
                CleanupUnpaidRegistrations::dispatch();
                $this->info('✅ Cleanup job dispatched');
            } else {
                $this->info('🔍 Would dispatch cleanup job');
            }
            
            $this->newLine();
        }

        $this->info('✨ Processing completed!');
        
        if (!$dryRun) {
            $this->comment('Jobs have been dispatched and will be processed by the queue worker.');
            $this->comment('Monitor logs for detailed execution results.');
        }

        return Command::SUCCESS;
    }
}
