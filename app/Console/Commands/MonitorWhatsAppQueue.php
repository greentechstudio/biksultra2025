<?php

namespace App\Console\Commands;

use App\Services\WhatsAppQueueService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MonitorWhatsAppQueue extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'whatsapp:monitor';

    /**
     * The console command description.
     */
    protected $description = 'Monitor WhatsApp queue status and pending messages';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('📱 WhatsApp Queue Monitor');
        $this->newLine();

        // Database queue jobs
        $pendingJobs = DB::table('jobs')->where('queue', 'whatsapp')->count();
        $failedJobs = DB::table('failed_jobs')->count();

        // Cache-based queue (from WhatsAppQueueService)
        $queueService = app(WhatsAppQueueService::class);
        $queueStats = $queueService->getQueueStats();

        $this->table([
            'Metric', 'Count'
        ], [
            ['Database Queue (Pending)', $pendingJobs],
            ['Database Queue (Failed)', $failedJobs],
            ['Cache Queue (Total)', $queueStats['total'] ?? 0],
            ['Cache Queue (Pending)', $queueStats['pending'] ?? 0],
            ['Cache Queue (Processing)', $queueStats['processing'] ?? 0],
            ['Cache Queue (Failed)', $queueStats['failed'] ?? 0]
        ]);

        $this->newLine();

        if ($pendingJobs > 0) {
            $this->warn("⚠️  {$pendingJobs} pending WhatsApp messages in database queue");
            $this->comment("Run: php artisan queue:work --queue=whatsapp");
        } else {
            $this->info("✅ No pending WhatsApp messages in database queue");
        }

        if ($failedJobs > 0) {
            $this->error("❌ {$failedJobs} failed jobs found");
            $this->comment("Run: php artisan queue:retry all");
        }

        return Command::SUCCESS;
    }
}
