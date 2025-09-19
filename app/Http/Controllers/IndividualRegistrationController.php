<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Services\IndividualRegistrationService;
use App\Services\RecaptchaService;

class IndividualRegistrationController extends Controller
{
    protected $individualRegistrationService;
    protected $recaptchaService;

    public function __construct(
        IndividualRegistrationService $individualRegistrationService,
        RecaptchaService $recaptchaService
    ) {
        $this->individualRegistrationService = $individualRegistrationService;
        $this->recaptchaService = $recaptchaService;
    }

    /**
     * Process individual registration
     */
    public function register(Request $request): JsonResponse
    {
        try {
            // Verify reCAPTCHA
            if (!$this->recaptchaService->verify($request->input('g-recaptcha-response'))) {
                return response()->json([
                    'success' => false,
                    'error' => 'reCAPTCHA verification failed'
                ], 400);
            }

            // Process registration
            $result = $this->individualRegistrationService->processRegistration($request);

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'message' => 'Registration successful',
                    'data' => [
                        'user_id' => $result['user_id'],
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
                'error' => 'Registration failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get individual registration form data (ticket types, etc.)
     */
    public function getFormData(): JsonResponse
    {
        try {
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
                    'genders' => ['laki-laki', 'perempuan']
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to load form data: ' . $e->getMessage()
            ], 500);
        }
    }
}