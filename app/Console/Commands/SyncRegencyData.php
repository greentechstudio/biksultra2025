<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Log;

class SyncRegencyData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:regency-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync provinces and regencies data to Redis from API';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting regency data synchronization...');

        try {
            // Get provinces data
            $this->info('Fetching provinces data...');
            $provincesResponse = Http::timeout(30)->get('https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json');
            
            if (!$provincesResponse->successful()) {
                $this->error('Failed to fetch provinces data');
                return 1;
            }

            $provinces = $provincesResponse->json();
            $this->info('Found ' . count($provinces) . ' provinces');

            // Store provinces to Redis
            Redis::del('provinces');
            Redis::set('provinces', json_encode($provinces));

            // Initialize regencies storage
            $allRegencies = [];

            // Get regencies for each province
            foreach ($provinces as $province) {
                $this->info("Fetching regencies for {$province['name']}...");
                
                $regenciesResponse = Http::timeout(30)->get("https://www.emsifa.com/api-wilayah-indonesia/api/regencies/{$province['id']}.json");
                
                if ($regenciesResponse->successful()) {
                    $regencies = $regenciesResponse->json();
                    
                    // Add province info to each regency
                    foreach ($regencies as &$regency) {
                        $regency['province_id'] = $province['id'];
                        $regency['province_name'] = $province['name'];
                        $allRegencies[] = $regency;
                    }
                    
                    // Store regencies by province to Redis
                    Redis::set("regencies:province:{$province['id']}", json_encode($regencies));
                    
                    $this->info("Stored " . count($regencies) . " regencies for {$province['name']}");
                } else {
                    $this->warn("Failed to fetch regencies for {$province['name']}");
                }

                // Small delay to be respectful to the API
                usleep(100000); // 0.1 second
            }

            // Store all regencies for search purposes
            Redis::set('regencies:all', json_encode($allRegencies));

            // Create search index for regencies (lowercase names for search)
            $searchIndex = [];
            foreach ($allRegencies as $regency) {
                $searchKey = strtolower($regency['name']);
                $searchIndex[$searchKey] = $regency;
            }
            Redis::set('regencies:search', json_encode($searchIndex));

            $this->info('Total regencies stored: ' . count($allRegencies));
            $this->info('Regency data synchronization completed successfully!');

            return 0;
        } catch (\Exception $e) {
            $this->error('Error during synchronization: ' . $e->getMessage());
            Log::error('Regency sync error: ' . $e->getMessage());
            return 1;
        }
    }
}
