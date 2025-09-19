<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Services\WakafRegistrationService;
use App\Services\RecaptchaService;

class WakafRegistrationController extends Controller
{
    protected $wakafRegistrationService;
    protected $recaptchaService;

    public function __construct(
        WakafRegistrationService $wakafRegistrationService,
        RecaptchaService $recaptchaService
    ) {
        $this->wakafRegistrationService = $wakafRegistrationService;
        $this->recaptchaService = $recaptchaService;
    }

    /**
     * Process wakaf registration
     */
    public function register(Request $request): JsonResponse
    {
        try {
            // Check if wakaf registration feature is enabled
            if (!config('features.wakaf_registration.enabled')) {
                return response()->json([
                    'success' => false,
                    'error' => config('features.disabled_messages.wakaf_registration'),
                    'feature_disabled' => true
                ], 403);
            }

            // Verify reCAPTCHA
            if (!$this->recaptchaService->verify($request->input('g-recaptcha-response'))) {
                return response()->json([
                    'success' => false,
                    'error' => 'reCAPTCHA verification failed'
                ], 400);
            }

            // Process registration
            $result = $this->wakafRegistrationService->processRegistration($request);

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'message' => 'Wakaf registration successful',
                    'data' => [
                        'user_id' => $result['user_id'],
                        'wakaf_id' => $result['wakaf_id'],
                        'donation_url' => $result['donation_url'],
                        'external_id' => $result['external_id']
                    ]
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'error' => $result['error']
                ], 400);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Wakaf registration failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Process webhook from SatuWakaf
     */
    public function webhook(Request $request): JsonResponse
    {
        try {
            $result = $this->wakafRegistrationService->processWebhook($request);

            if ($result['success']) {
                return response()->json(['success' => true]);
            } else {
                return response()->json([
                    'success' => false,
                    'error' => $result['error']
                ], 400);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Webhook processing failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get wakaf information
     */
    public function getInfo(): JsonResponse
    {
        try {
            // Check if wakaf registration feature is enabled
            if (!config('features.wakaf_registration.enabled')) {
                return response()->json([
                    'success' => false,
                    'error' => config('features.disabled_messages.wakaf_registration'),
                    'feature_disabled' => true
                ], 403);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'minimum_amount' => 10000,
                    'suggested_amounts' => [25000, 50000, 100000, 250000, 500000],
                    'project_description' => 'Wakaf untuk membantu penyelenggaraan acara lari amal Amazing Sultra Run',
                    'benefits' => [
                        'Mendapat pahala wakaf yang terus mengalir',
                        'Membantu kegiatan sosial dan olahraga',
                        'Sertifikat wakaf digital',
                        'Laporan penggunaan dana wakaf'
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to get wakaf info: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user's wakaf history
     */
    public function getHistory(Request $request): JsonResponse
    {
        try {
            $email = $request->input('email');
            
            if (!$email) {
                return response()->json([
                    'success' => false,
                    'error' => 'Email is required'
                ], 400);
            }

            $user = \App\Models\User::where('email', $email)->first();
            
            if (!$user) {
                return response()->json([
                    'success' => true,
                    'data' => ['wakaf_registrations' => []]
                ]);
            }

            $wakafRegistrations = $user->wakafRegistrations()
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'wakaf_registrations' => $wakafRegistrations->map(function ($wakaf) {
                        return [
                            'id' => $wakaf->id,
                            'amount' => $wakaf->amount,
                            'message' => $wakaf->message,
                            'status' => $wakaf->status,
                            'created_at' => $wakaf->created_at->format('d/m/Y H:i'),
                        ];
                    })
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to get wakaf history: ' . $e->getMessage()
            ], 500);
        }
    }
}