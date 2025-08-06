<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Predis\Client as RedisClient;

class CacheLocationData extends Command
{
    private $redis;
    
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'location:cache {--refresh : Refresh the cache}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cache provinces and regencies data from API to Redis';

    public function __construct()
    {
        parent::__construct();
        
        try {
            $this->redis = new RedisClient([
                'host' => '127.0.0.1',
                'port' => 60977,
                'password' => 'GSozWrvKtn18hzjCZ6j',
                'timeout' => 5
            ]);
        } catch (\Exception $e) {
            Log::error('Redis connection failed in CacheLocationData', ['error' => $e->getMessage()]);
            $this->redis = null;
        }
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (!$this->redis) {
            $this->error('Redis connection not available');
            return 1;
        }
        
        $this->info('Starting to cache location data...');
        
        try {
            // Show current cache status
            $this->showCacheStatus();
            
            // Clear existing cache if refresh option is used
            if ($this->option('refresh')) {
                $this->redis->del('provinces');
                $this->redis->del('all_regencies');
                // Clear regencies cache with pattern
                for ($i = 11; $i <= 94; $i++) {
                    $this->redis->del("regencies:{$i}");
                }
                $this->info('Cleared existing cache...');
            }
            
            // Cache provinces
            $this->cacheProvinces();
            
            // Cache all regencies
            $this->cacheAllRegencies();
            
            $this->info('Location data cached successfully!');
            
        } catch (\Exception $e) {
            $this->error('Error caching location data: ' . $e->getMessage());
            Log::error('Location cache error: ' . $e->getMessage());
            return 1;
        }
        
        return 0;
    }
    
    private function showCacheStatus()
    {
        $this->info('=== Current Cache Status ===');
        
        $provincesExists = $this->redis->exists('provinces');
        $allRegenciesExists = $this->redis->exists('all_regencies');
        
        $this->line('Provinces cache: ' . ($provincesExists ? '✅ EXISTS' : '❌ NOT FOUND'));
        $this->line('All regencies cache: ' . ($allRegenciesExists ? '✅ EXISTS' : '❌ NOT FOUND'));
        
        if ($provincesExists) {
            $provincesJson = $this->redis->get('provinces');
            $provinces = json_decode($provincesJson, true);
            $this->line('Provinces count: ' . count($provinces));
        }
        
        if ($allRegenciesExists) {
            $allRegenciesJson = $this->redis->get('all_regencies');
            $allRegencies = json_decode($allRegenciesJson, true);
            $this->line('Regencies count: ' . count($allRegencies));
        }
        
        $this->line('==============================');
        $this->newLine();
    }
    
    private function cacheProvinces()
    {
        // Check if provinces already cached (unless refresh is requested)
        if (!$this->option('refresh') && $this->redis->exists('provinces')) {
            $this->info('Provinces already cached, skipping fetch...');
            $provincesJson = $this->redis->get('provinces');
            return json_decode($provincesJson, true);
        }
        
        $this->info('Fetching provinces...');
        
        $response = Http::timeout(60)
            ->withOptions([
                'verify' => false, // Disable SSL verification for local development
                'curl' => [
                    CURLOPT_SSL_VERIFYPEER => false,
                    CURLOPT_SSL_VERIFYHOST => false,
                ]
            ])
            ->get('https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json');
        
        if (!$response->successful()) {
            throw new \Exception('Failed to fetch provinces data');
        }
        
        $provinces = $response->json();
        
        // Cache provinces data permanently (no expiry)
        $this->redis->set('provinces', json_encode($provinces));
        
        $this->info('Cached ' . count($provinces) . ' provinces permanently');
        
        return $provinces;
    }
    
    private function cacheAllRegencies()
    {
        // Check if all regencies already cached (unless refresh is requested)
        if (!$this->option('refresh') && $this->redis->exists('all_regencies')) {
            $this->info('All regencies already cached, skipping fetch...');
            return;
        }
        
        $this->info('Fetching regencies for all provinces...');
        
        $provincesJson = $this->redis->get('provinces');
        $provinces = $provincesJson ? json_decode($provincesJson, true) : [];
        $allRegencies = [];
        
        $progressBar = $this->output->createProgressBar(count($provinces));
        $progressBar->start();
        
        foreach ($provinces as $province) {
            $response = Http::timeout(60)
                ->withOptions([
                    'verify' => false, // Disable SSL verification for local development
                    'curl' => [
                        CURLOPT_SSL_VERIFYPEER => false,
                        CURLOPT_SSL_VERIFYHOST => false,
                    ]
                ])
                ->get("https://www.emsifa.com/api-wilayah-indonesia/api/regencies/{$province['id']}.json");
            
            if ($response->successful()) {
                $regencies = $response->json();
                
                // Add province name to each regency
                foreach ($regencies as &$regency) {
                    $regency['province_name'] = $province['name'];
                    $allRegencies[] = $regency;
                }
                
                // Cache regencies by province permanently (no expiry)
                $this->redis->set("regencies:{$province['id']}", json_encode($regencies));
            }
            
            $progressBar->advance();
            usleep(100000); // Sleep 100ms to avoid rate limiting
        }
        
        $progressBar->finish();
        $this->newLine();
        
        // Cache all regencies for search permanently (no expiry)
        $this->redis->set('all_regencies', json_encode($allRegencies));
        
        $this->info('Cached ' . count($allRegencies) . ' regencies total permanently');
    }
}
