<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class FeatureController extends Controller
{
    /**
     * Get all features status
     */
    public function index(): JsonResponse
    {
        try {
            $features = config('features');
            
            // Remove disabled_messages from the response
            unset($features['disabled_messages']);
            
            $response = [];
            foreach ($features as $key => $feature) {
                $response[$key] = [
                    'name' => $feature['name'],
                    'description' => $feature['description'],
                    'enabled' => $feature['enabled'],
                    'status' => $feature['enabled'] ? 'active' : 'disabled'
                ];
            }

            return response()->json([
                'success' => true,
                'data' => $response
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to get features status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check if a specific feature is enabled
     */
    public function checkFeature(string $feature): JsonResponse
    {
        try {
            $featureConfig = config("features.{$feature}");
            
            if (!$featureConfig) {
                return response()->json([
                    'success' => false,
                    'error' => 'Feature not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'feature' => $feature,
                    'name' => $featureConfig['name'],
                    'enabled' => $featureConfig['enabled'],
                    'status' => $featureConfig['enabled'] ? 'active' : 'disabled',
                    'message' => $featureConfig['enabled'] ? 
                        'Feature is available' : 
                        config("features.disabled_messages.{$feature}", config('features.disabled_messages.default'))
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to check feature: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get enabled registration types only
     */
    public function getEnabledRegistrationTypes(): JsonResponse
    {
        try {
            $enabledTypes = [];
            
            if (config('features.individual_registration.enabled')) {
                $enabledTypes[] = [
                    'type' => 'individual',
                    'name' => 'Pendaftaran Individual',
                    'description' => 'Daftar sebagai peserta individual',
                    'endpoint' => '/api/individual/register'
                ];
            }

            if (config('features.collective_registration.enabled')) {
                $enabledTypes[] = [
                    'type' => 'collective',
                    'name' => 'Pendaftaran Kolektif',
                    'description' => 'Daftar sebagai grup/tim',
                    'endpoint' => '/api/collective/register'
                ];
            }

            if (config('features.wakaf_registration.enabled')) {
                $enabledTypes[] = [
                    'type' => 'wakaf',
                    'name' => 'Wakaf',
                    'description' => 'Donasi melalui wakaf',
                    'endpoint' => '/api/wakaf/register'
                ];
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'enabled_types' => $enabledTypes,
                    'total_enabled' => count($enabledTypes)
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to get enabled registration types: ' . $e->getMessage()
            ], 500);
        }
    }
}