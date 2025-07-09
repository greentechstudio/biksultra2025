<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class RegencyController extends Controller
{
    /**
     * Search regencies for autocomplete
     */
    public function search(Request $request)
    {
        $query = $request->get('q', '');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }

        try {
            // Get search index from Redis
            $searchData = Redis::get('regencies:search');
            
            if (!$searchData) {
                return response()->json([
                    'error' => 'Data not available. Please run: php artisan sync:regency-data'
                ], 404);
            }

            $regencies = json_decode($searchData, true);
            $results = [];

            // Search for regencies that contain the query string
            $queryLower = strtolower($query);
            
            foreach ($regencies as $name => $regency) {
                if (strpos($name, $queryLower) !== false) {
                    $results[] = [
                        'id' => $regency['id'],
                        'name' => $regency['name'],
                        'province_name' => $regency['province_name'],
                        'display' => $regency['name'] . ', ' . $regency['province_name']
                    ];
                    
                    // Limit results to 10
                    if (count($results) >= 10) {
                        break;
                    }
                }
            }

            return response()->json($results);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Search failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all provinces
     */
    public function provinces()
    {
        try {
            $provincesData = Redis::get('provinces');
            
            if (!$provincesData) {
                return response()->json([
                    'error' => 'Data not available. Please run: php artisan sync:regency-data'
                ], 404);
            }

            $provinces = json_decode($provincesData, true);
            
            return response()->json($provinces);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to get provinces: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get regencies by province
     */
    public function regenciesByProvince($provinceId)
    {
        try {
            $regenciesData = Redis::get("regencies:province:{$provinceId}");
            
            if (!$regenciesData) {
                return response()->json([
                    'error' => 'Data not available for this province'
                ], 404);
            }

            $regencies = json_decode($regenciesData, true);
            
            return response()->json($regencies);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to get regencies: ' . $e->getMessage()
            ], 500);
        }
    }
}
