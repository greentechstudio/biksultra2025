<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Predis\Client as RedisClient;

class LocationController extends Controller
{
    private $redis;
    
    public function __construct()
    {
        try {
            $this->redis = new RedisClient([
                'host' => '127.0.0.1',
                'port' => 6379,
                'timeout' => 5
            ]);
        } catch (\Exception $e) {
            \Log::error('Redis connection failed', ['error' => $e->getMessage()]);
            $this->redis = null;
        }
    }

    /**
     * Search regencies based on query string
     */
    public function searchRegencies(Request $request)
    {
        $query = $request->get('q', '');
        
        \Log::info('Location search request', ['query' => $query]);
        
        if (strlen($query) < 2) {
            \Log::info('Query too short', ['length' => strlen($query)]);
            return response()->json([]);
        }
        
        try {
            if (!$this->redis) {
                \Log::error('Redis not available');
                return response()->json(['error' => 'Redis not available'], 500);
            }
            
            $allRegenciesJson = $this->redis->get('all_regencies');
            \Log::info('Redis data retrieved', ['has_data' => !empty($allRegenciesJson)]);
            
            if (!$allRegenciesJson) {
                \Log::warning('No data in Redis');
                return response()->json([]);
            }
            
            $allRegencies = is_string($allRegenciesJson) ? json_decode($allRegenciesJson, true) : $allRegenciesJson;
            
            \Log::info('Decoded regencies', ['count' => count($allRegencies)]);
            
            $filteredRegencies = array_filter($allRegencies, function ($regency) use ($query) {
                return stripos($regency['name'], $query) !== false;
            });
            
            \Log::info('Filtered results', ['count' => count($filteredRegencies)]);
            
            // Format the results for autocomplete
            $results = array_map(function ($regency) {
                return [
                    'id' => $regency['id'],
                    'name' => $regency['name'],
                    'province_name' => $regency['province_name'],
                    'label' => $regency['name'] . ', ' . $regency['province_name']
                ];
            }, array_slice($filteredRegencies, 0, 10)); // Limit to 10 results
            
            \Log::info('Final results', ['count' => count($results)]);
            
            return response()->json(array_values($results));
            
        } catch (\Exception $e) {
            \Log::error('Location search error', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Search failed'], 500);
        }
    }
    
    /**
     * Get regency details by ID
     */
    public function getRegency($id)
    {
        try {
            if (!$this->redis) {
                \Log::error('Redis not available');
                return response()->json(['error' => 'Redis not available'], 500);
            }
            
            $allRegenciesJson = $this->redis->get('all_regencies');
            
            if (!$allRegenciesJson) {
                return response()->json(['error' => 'Data not found'], 404);
            }
            
            $allRegencies = json_decode($allRegenciesJson, true);
            
            $regency = array_filter($allRegencies, function ($r) use ($id) {
                return $r['id'] == $id;
            });
            
            if (empty($regency)) {
                return response()->json(['error' => 'Regency not found'], 404);
            }
            
            return response()->json(array_values($regency)[0]);
            
        } catch (\Exception $e) {
            \Log::error('Get regency error', ['error' => $e->getMessage(), 'id' => $id]);
            return response()->json(['error' => 'Failed to get regency'], 500);
        }
    }
}
