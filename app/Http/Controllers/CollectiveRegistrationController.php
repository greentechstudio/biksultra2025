<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Services\CollectiveRegistrationService;
use App\Services\RecaptchaService;

class CollectiveRegistrationController extends Controller
{
    protected $collectiveRegistrationService;
    protected $recaptchaService;

    public function __construct(
        CollectiveRegistrationService $collectiveRegistrationService,
        RecaptchaService $recaptchaService
    ) {
        $this->collectiveRegistrationService = $collectiveRegistrationService;
        $this->recaptchaService = $recaptchaService;
    }

    /**
     * Process collective registration
     */
    public function register(Request $request): JsonResponse
    {
        try {
            // Check if collective registration feature is enabled
            if (!config('features.collective_registration.enabled')) {
                return response()->json([
                    'success' => false,
                    'error' => config('features.disabled_messages.collective_registration'),
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
            $result = $this->collectiveRegistrationService->processRegistration($request);

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'message' => 'Collective registration successful',
                    'data' => [
                        'collective_id' => $result['collective_id'],
                        'primary_user_id' => $result['primary_user_id'],
                        'participants_count' => $result['participants_count'],
                        'total_price' => $result['total_price'],
                        'invoice_url' => $result['invoice_url'],
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
                'error' => 'Collective registration failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get collective registration form data
     */
    public function getFormData(): JsonResponse
    {
        try {
            // Check if collective registration feature is enabled
            if (!config('features.collective_registration.enabled')) {
                return response()->json([
                    'success' => false,
                    'error' => config('features.disabled_messages.collective_registration'),
                    'feature_disabled' => true
                ], 403);
            }

            $ticketTypes = \App\Models\TicketType::with('raceCategory')
                ->where('is_active', true)
                ->where('start_date', '<=', now())
                ->where('end_date', '>=', now())
                ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'ticket_types' => $ticketTypes,
                    'jersey_sizes' => ['XS', 'S', 'M', 'L', 'XL', 'XXL'],
                    'genders' => ['laki-laki', 'perempuan'],
                    'max_participants' => config('app.collective_max_participants', 50)
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to load form data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get collective registration limits and pricing
     */
    public function getLimits(Request $request): JsonResponse
    {
        try {
            // Check if collective registration feature is enabled
            if (!config('features.collective_registration.enabled')) {
                return response()->json([
                    'success' => false,
                    'error' => config('features.disabled_messages.collective_registration'),
                    'feature_disabled' => true
                ], 403);
            }

            $ticketTypeId = $request->input('ticket_type_id');
            
            if (!$ticketTypeId) {
                return response()->json([
                    'success' => false,
                    'error' => 'Ticket type ID is required'
                ], 400);
            }

            $ticketType = \App\Models\TicketType::findOrFail($ticketTypeId);
            
            return response()->json([
                'success' => true,
                'data' => [
                    'price_per_participant' => $ticketType->price,
                    'min_participants' => config('app.collective_min_participants', 5),
                    'max_participants' => config('app.collective_max_participants', 50),
                    'available_quota' => $ticketType->quota ? ($ticketType->quota - $ticketType->registered_count) : null
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to get limits: ' . $e->getMessage()
            ], 500);
        }
    }
}