<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\XenditService;

class TestXenditService extends Command
{
    protected $signature = 'xendit:test';
    protected $description = 'Test Xendit service configuration';

    public function handle()
    {
        $this->info('Testing Xendit Service Configuration...');
        
        try {
            $service = new XenditService();
            $this->info('✅ XenditService instantiated successfully!');
            
            $this->info('Base URL: ' . config('xendit.base_url'));
            $this->info('Secret Key: ' . (config('xendit.secret_key') ? 'SET' : 'NOT SET'));
            $this->info('Webhook Token: ' . (config('xendit.webhook_token') ? 'SET' : 'NOT SET'));
            
        } catch (\Exception $e) {
            $this->error('❌ XenditService instantiation failed: ' . $e->getMessage());
        }
    }
}
