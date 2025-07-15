<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Register custom Artisan commands.
     */
    protected $commands = [
        \App\Console\Commands\ProcessUnpaidRegistrations::class,
        \App\Console\Commands\RunScheduler::class,
        \App\Console\Commands\CleanupUnpaidUsers::class,
    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Send payment reminders every 30 minutes
        $schedule->command('registrations:process-unpaid --reminders')
            ->everyThirtyMinutes()
            ->withoutOverlapping()
            ->appendOutputTo(storage_path('logs/payment-reminders.log'));

        // Cleanup unpaid users after 24 hours - run every hour
        $schedule->command('cleanup:unpaid-users')
            ->hourly()
            ->withoutOverlapping()
            ->appendOutputTo(storage_path('logs/cleanup-unpaid-24h.log'));
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}
