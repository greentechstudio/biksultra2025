<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class RunScheduler extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schedule:work {--sleep=60 : Number of seconds to sleep between runs}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start the scheduler worker (alternative to cron)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $sleep = (int) $this->option('sleep');
        
        $this->info('Starting scheduler worker...');
        $this->info('Press Ctrl+C to stop.');
        
        while (true) {
            try {
                $this->line('[' . now()->format('Y-m-d H:i:s') . '] Running scheduled tasks...');
                
                // Run the scheduler
                Artisan::call('schedule:run');
                $output = Artisan::output();
                
                if (trim($output) !== 'No scheduled commands are ready to run.') {
                    $this->line($output);
                }
                
                // Sleep for the specified time
                sleep($sleep);
                
            } catch (\Exception $e) {
                $this->error('Error running scheduler: ' . $e->getMessage());
                sleep($sleep);
            }
        }
    }
}
