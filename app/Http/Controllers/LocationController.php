<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
            Log::error('Redis connection failed', ['error' => $e->getMessage()]);
            $this->redis = null;
        }
    }

    /**
     * Search regencies based on query string
     */
    public function searchRegencies(Request $request)
    {
        $query = $request->get('q', '');
        
        Log::info('Location search request', ['query' => $query]);
        
        if (strlen($query) < 2) {
            Log::info('Query too short', ['length' => strlen($query)]);
            return response()->json([]);
        }
        
        try {
            if (!$this->redis) {
                Log::error('Redis not available');
                return response()->json(['error' => 'Redis not available'], 500);
            }
            
            $allRegenciesJson = $this->redis->get('all_regencies');
            Log::info('Redis data retrieved', ['has_data' => !empty($allRegenciesJson)]);
            
            if (!$allRegenciesJson) {
                Log::warning('No data in Redis');
                return response()->json([]);
            }
            
            $allRegencies = is_string($allRegenciesJson) ? json_decode($allRegenciesJson, true) : $allRegenciesJson;
            
            Log::info('Decoded regencies', ['count' => count($allRegencies)]);
            
            $filteredRegencies = array_filter($allRegencies, function ($regency) use ($query) {
                return stripos($regency['name'], $query) !== false;
            });
            
            Log::info('Filtered results', ['count' => count($filteredRegencies)]);
            
            // Format the results for autocomplete
            $results = array_map(function ($regency) {
                return [
                    'id' => $regency['id'],
                    'name' => $regency['name'],
                    'province_name' => $regency['province_name'],
                    'label' => $regency['name'] . ', ' . $regency['province_name']
                ];
            }, array_slice($filteredRegencies, 0, 10)); // Limit to 10 results
            
            Log::info('Final results', ['count' => count($results)]);
            
            return response()->json(array_values($results));
            
        } catch (\Exception $e) {
            Log::error('Location search error', ['error' => $e->getMessage()]);
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
                Log::error('Redis not available');
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
            Log::error('Get regency error', ['error' => $e->getMessage(), 'id' => $id]);
            return response()->json(['error' => 'Failed to get regency'], 500);
        }
    }
    
    /**
     * Smart search for locations - returns best match with complete data
     */
    public function smartSearch(Request $request)
    {
        $query = $request->get('q', '');
        
        Log::info('Smart location search request', ['query' => $query]);
        
        if (strlen($query) < 2) {
            return response()->json([
                'success' => false,
                'message' => 'Query too short'
            ]);
        }
        
        try {
            if (!$this->redis) {
                Log::error('Redis not available');
                return response()->json([
                    'success' => false,
                    'message' => 'Location service not available'
                ], 500);
            }
            
            $allRegenciesJson = $this->redis->get('all_regencies');
            
            if (!$allRegenciesJson) {
                Log::warning('No data in Redis');
                return response()->json([
                    'success' => false,
                    'message' => 'Location data not available'
                ]);
            }
            
            $allRegencies = is_string($allRegenciesJson) ? json_decode($allRegenciesJson, true) : $allRegenciesJson;
            
            // Smart search algorithm
            $exactMatches = [];
            $partialMatches = [];
            $fuzzyMatches = [];
            
            foreach ($allRegencies as $regency) {
                $name = strtolower($regency['name']);
                $queryLower = strtolower($query);
                
                // Exact match
                if ($name === $queryLower) {
                    $exactMatches[] = $regency;
                }
                // Starts with query
                elseif (strpos($name, $queryLower) === 0) {
                    $partialMatches[] = $regency;
                }
                // Contains query
                elseif (strpos($name, $queryLower) !== false) {
                    $fuzzyMatches[] = $regency;
                }
            }
            
            // Combine results with priority
            $results = array_merge($exactMatches, $partialMatches, $fuzzyMatches);
            
            // Format results
            $formattedResults = array_map(function ($regency) {
                return [
                    'regency_id' => $regency['id'],
                    'regency_name' => $regency['name'],
                    'province_name' => $regency['province_name'],
                    'full_name' => $regency['name'] . ', ' . $regency['province_name'],
                    'match_score' => $this->calculateMatchScore($regency['name'], request()->get('q'))
                ];
            }, array_slice($results, 0, 10));
            
            // Sort by match score
            usort($formattedResults, function($a, $b) {
                return $b['match_score'] - $a['match_score'];
            });
            
            return response()->json([
                'success' => true,
                'data' => $formattedResults,
                'total' => count($formattedResults)
            ]);
            
        } catch (\Exception $e) {
            Log::error('Smart location search error', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Search failed'
            ], 500);
        }
    }
    
    /**
     * Calculate match score for sorting
     */
    private function calculateMatchScore($name, $query)
    {
        $nameLower = strtolower($name);
        $queryLower = strtolower($query);
        
        // Exact match gets highest score
        if ($nameLower === $queryLower) {
            return 100;
        }
        
        // Starts with query gets high score
        if (strpos($nameLower, $queryLower) === 0) {
            return 80;
        }
        
        // Contains query gets medium score
        if (strpos($nameLower, $queryLower) !== false) {
            return 60;
        }
        
        // Similar length gets bonus
        $lengthDiff = abs(strlen($nameLower) - strlen($queryLower));
        $lengthBonus = max(0, 20 - $lengthDiff);
        
        return 40 + $lengthBonus;
    }
}
