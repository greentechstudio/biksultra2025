<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Console\Commands\ProcessUnpaidRegistrations;
use App\Console\Commands\RunScheduler;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Register custom commands
Artisan::command('registrations:process-unpaid', ProcessUnpaidRegistrations::class)
    ->purpose('Process unpaid registrations: send reminders and cleanup expired ones');

// Register scheduler worker command
Artisan::command('schedule:work', RunScheduler::class)
    ->purpose('Start the scheduler worker (alternative to cron)');
