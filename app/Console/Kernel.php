<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Send payment reminders every 5 minutes
        // (The command will determine if a user needs a reminder based on 30-minute intervals)
        $schedule->command('registrations:process-unpaid', ['--reminders-only'])
            ->everyFiveMinutes()
            ->withoutOverlapping()
            ->appendOutputTo(storage_path('logs/payment-reminders.log'));
        
        // Cleanup unpaid registrations every 30 minutes
        $schedule->command('registrations:process-unpaid', ['--cleanup-only'])
            ->everyThirtyMinutes()
            ->withoutOverlapping()
            ->appendOutputTo(storage_path('logs/cleanup-unpaid.log'));
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
