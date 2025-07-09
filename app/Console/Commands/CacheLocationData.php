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
                'port' => 6379,
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
    
    private function cacheProvinces()
    {
        $this->info('Fetching provinces...');
        
        $response = Http::timeout(30)->get('https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json');
        
        if (!$response->successful()) {
            throw new \Exception('Failed to fetch provinces data');
        }
        
        $provinces = $response->json();
        
        // Cache provinces data (7 days = 604800 seconds)
        $this->redis->setex('provinces', 604800, json_encode($provinces));
        
        $this->info('Cached ' . count($provinces) . ' provinces');
        
        return $provinces;
    }
    
    private function cacheAllRegencies()
    {
        $this->info('Fetching regencies for all provinces...');
        
        $provincesJson = $this->redis->get('provinces');
        $provinces = $provincesJson ? json_decode($provincesJson, true) : [];
        $allRegencies = [];
        
        $progressBar = $this->output->createProgressBar(count($provinces));
        $progressBar->start();
        
        foreach ($provinces as $province) {
            $response = Http::timeout(30)->get("https://www.emsifa.com/api-wilayah-indonesia/api/regencies/{$province['id']}.json");
            
            if ($response->successful()) {
                $regencies = $response->json();
                
                // Add province name to each regency
                foreach ($regencies as &$regency) {
                    $regency['province_name'] = $province['name'];
                    $allRegencies[] = $regency;
                }
                
                // Cache regencies by province (7 days = 604800 seconds)
                $this->redis->setex("regencies:{$province['id']}", 604800, json_encode($regencies));
            }
            
            $progressBar->advance();
            usleep(100000); // Sleep 100ms to avoid rate limiting
        }
        
        $progressBar->finish();
        $this->newLine();
        
        // Cache all regencies for search (7 days = 604800 seconds)
        $this->redis->setex('all_regencies', 604800, json_encode($allRegencies));
        
        $this->info('Cached ' . count($allRegencies) . ' regencies total');
    }
}
