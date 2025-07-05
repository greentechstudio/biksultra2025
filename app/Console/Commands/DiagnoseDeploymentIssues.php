<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\XenditService;

class DiagnoseDeploymentIssues extends Command
{
    protected $signature = 'deployment:diagnose';
    protected $description = 'Diagnose common deployment issues';

    public function handle()
    {
        $this->info('🔍 Diagnosing Deployment Issues...');
        $this->newLine();
        
        // 1. Check Environment Variables
        $this->checkEnvironmentVariables();
        
        // 2. Check Configuration Loading
        $this->checkConfigurationLoading();
        
        // 3. Check Service Instantiation
        $this->checkServiceInstantiation();
        
        // 4. Check Database Connection
        $this->checkDatabaseConnection();
        
        // 5. Check File Permissions
        $this->checkFilePermissions();
        
        $this->newLine();
        $this->info('✅ Diagnosis complete!');
    }
    
    private function checkEnvironmentVariables()
    {
        $this->info('1. Checking Environment Variables...');
        
        $vars = [
            'APP_ENV' => env('APP_ENV'),
            'APP_DEBUG' => env('APP_DEBUG'),
            'APP_URL' => env('APP_URL'),
            'DB_CONNECTION' => env('DB_CONNECTION'),
            'DB_HOST' => env('DB_HOST'),
            'DB_DATABASE' => env('DB_DATABASE'),
            'XENDIT_BASE_URL' => env('XENDIT_BASE_URL'),
            'XENDIT_SECRET_KEY' => env('XENDIT_SECRET_KEY') ? 'SET' : 'NOT SET',
            'XENDIT_WEBHOOK_TOKEN' => env('XENDIT_WEBHOOK_TOKEN') ? 'SET' : 'NOT SET',
            'WHATSAPP_API_KEY' => env('WHATSAPP_API_KEY') ? 'SET' : 'NOT SET',
        ];
        
        foreach ($vars as $key => $value) {
            $status = $value ? '✅' : '❌';
            $this->line("   {$status} {$key}: " . ($value ?: 'NOT SET'));
        }
        
        $this->newLine();
    }
    
    private function checkConfigurationLoading()
    {
        $this->info('2. Checking Configuration Loading...');
        
        $configs = [
            'app.env' => config('app.env'),
            'app.debug' => config('app.debug'),
            'app.url' => config('app.url'),
            'database.default' => config('database.default'),
            'xendit.base_url' => config('xendit.base_url'),
            'xendit.secret_key' => config('xendit.secret_key') ? 'SET' : 'NOT SET',
            'xendit.webhook_token' => config('xendit.webhook_token') ? 'SET' : 'NOT SET',
        ];
        
        foreach ($configs as $key => $value) {
            $status = $value ? '✅' : '❌';
            $this->line("   {$status} {$key}: " . ($value ?: 'NOT SET'));
        }
        
        $this->newLine();
    }
    
    private function checkServiceInstantiation()
    {
        $this->info('3. Checking Service Instantiation...');
        
        try {
            $xenditService = new XenditService();
            $this->line('   ✅ XenditService: OK');
        } catch (\Exception $e) {
            $this->line('   ❌ XenditService: ' . $e->getMessage());
        }
        
        $this->newLine();
    }
    
    private function checkDatabaseConnection()
    {
        $this->info('4. Checking Database Connection...');
        
        try {
            \DB::connection()->getPdo();
            $this->line('   ✅ Database connection: OK');
        } catch (\Exception $e) {
            $this->line('   ❌ Database connection: ' . $e->getMessage());
        }
        
        $this->newLine();
    }
    
    private function checkFilePermissions()
    {
        $this->info('5. Checking File Permissions...');
        
        $paths = [
            'storage/logs' => storage_path('logs'),
            'storage/app' => storage_path('app'),
            'storage/framework' => storage_path('framework'),
            'bootstrap/cache' => base_path('bootstrap/cache'),
        ];
        
        foreach ($paths as $name => $path) {
            $writable = is_writable($path);
            $status = $writable ? '✅' : '❌';
            $this->line("   {$status} {$name}: " . ($writable ? 'Writable' : 'Not writable'));
        }
        
        $this->newLine();
    }
}
