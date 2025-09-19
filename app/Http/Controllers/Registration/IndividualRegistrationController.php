<?php

namespace App\Http\Controllers\Registration;

use App\Http\Controllers\Controller;
use App\Models\JerseySize;
use App\Models\RaceCategory;
use App\Models\BloodType;
use App\Models\EventSource;
use App\Models\TicketType;
use App\Services\Registration\IndividualRegistrationService;
use App\Services\RecaptchaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class IndividualRegistrationController extends Controller
{
    private $registrationService;
    private $recaptchaService;

    public function __construct(
        IndividualRegistrationService $registrationService,
        RecaptchaService $recaptchaService
    ) {
        $this->registrationService = $registrationService;
        $this->recaptchaService = $recaptchaService;
    }

    /**
     * Show the registration form with manual password
     */
    public function showRegister()
    {
        $jerseySizes = JerseySize::active()->get();
        $raceCategories = RaceCategory::active()->get();
        $bloodTypes = BloodType::active()->get();
        $eventSources = EventSource::active()->get();
        $ticketTypes = TicketType::active()->get();

        return view('auth.register', compact(
            'jerseySizes', 
            'raceCategories', 
            'bloodTypes', 
            'eventSources',
            'ticketTypes'
        ));
    }

    /**
     * Show the registration form with random password option
     */
    public function showRegisterRandomPassword()
    {
        $jerseySizes = JerseySize::active()->get();
        $raceCategories = RaceCategory::active()->get();
        $bloodTypes = BloodType::active()->get();
        $eventSources = EventSource::active()->get();
        $ticketTypes = TicketType::active()->get();

        return view('auth.register-random-password', compact(
            'jerseySizes', 
            'raceCategories', 
            'bloodTypes', 
            'eventSources',
            'ticketTypes'
        ));
    }

    /**
     * Handle individual registration with manual password
     */
    public function register(Request $request)
    {
        // Validate reCAPTCHA
        $recaptchaResult = $this->recaptchaService->verify($request->input('g-recaptcha-response'));
        if (!$recaptchaResult['success']) {
            return back()
                ->withErrors(['recaptcha' => 'reCAPTCHA verification failed. Please try again.'])
                ->withInput();
        }

        // Validate form data
        $validator = $this->getRegistrationValidator($request->all(), true);
        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        // Check ticket availability
        $ticketType = TicketType::findOrFail($request->ticket_type_id);
        if ($ticketType->registered_count >= $ticketType->quota) {
            return back()
                ->withErrors(['ticket_type_id' => 'Ticket quota for this category is full.'])
                ->withInput();
        }

        // Process registration
        $result = $this->registrationService->registerWithManualPassword($validator->validated());

        if ($result['success']) {
            // Login user
            Auth::login($result['user']);
            
            return redirect()->route('dashboard')
                ->with('success', 'Registration successful! Please complete your payment.')
                ->with('invoice_url', $result['invoice_url']);
        } else {
            return back()
                ->withErrors(['registration' => $result['message']])
                ->withInput();
        }
    }

    /**
     * Handle individual registration with random password
     */
    public function registerWithRandomPassword(Request $request)
    {
        // Validate reCAPTCHA
        $recaptchaResult = $this->recaptchaService->verify($request->input('g-recaptcha-response'));
        if (!$recaptchaResult['success']) {
            return back()
                ->withErrors(['recaptcha' => 'reCAPTCHA verification failed. Please try again.'])
                ->withInput();
        }

        // Validate form data (no password required)
        $validator = $this->getRegistrationValidator($request->all(), false);
        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        // Check ticket availability
        $ticketType = TicketType::findOrFail($request->ticket_type_id);
        if ($ticketType->registered_count >= $ticketType->quota) {
            return back()
                ->withErrors(['ticket_type_id' => 'Ticket quota for this category is full.'])
                ->withInput();
        }

        // Process registration
        $result = $this->registrationService->registerWithRandomPassword($validator->validated());

        if ($result['success']) {
            // Login user
            Auth::login($result['user']);
            
            $message = 'Registration successful! ';
            if ($result['password_sent']) {
                $message .= 'Your password has been sent to your WhatsApp. ';
            }
            $message .= 'Please complete your payment.';
            
            return redirect()->route('dashboard')
                ->with('success', $message)
                ->with('invoice_url', $result['invoice_url']);
        } else {
            return back()
                ->withErrors(['registration' => $result['message']])
                ->withInput();
        }
    }

    /**
     * Get registration validator
     */
    private function getRegistrationValidator(array $data, bool $requirePassword = true): \Illuminate\Validation\Validator
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'whatsapp_number' => 'required|string|max:20',
            'date_of_birth' => 'required|date|before:today',
            'gender' => 'required|in:male,female',
            'blood_type_id' => 'required|exists:blood_types,id',
            'emergency_contact_name' => 'required|string|max:255',
            'emergency_contact_phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'race_category_id' => 'required|exists:race_categories,id',
            'jersey_size_id' => 'required|exists:jersey_sizes,id',
            'ticket_type_id' => 'required|exists:ticket_types,id',
            'event_source_id' => 'nullable|exists:event_sources,id',
            'terms_accepted' => 'required|accepted',
        ];

        if ($requirePassword) {
            $rules['password'] = 'required|string|min:8|confirmed';
        }

        return Validator::make($data, $rules);
    }

    /**
     * API endpoint for individual registration
     */
    public function registerApi(Request $request)
    {
        try {
            // Validate API request
            $validator = $this->getRegistrationValidator($request->all(), false);
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Check ticket availability
            $ticketType = TicketType::findOrFail($request->ticket_type_id);
            if ($ticketType->registered_count >= $ticketType->quota) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ticket quota for this category is full.'
                ], 400);
            }

            // Process registration
            $result = $this->registrationService->registerWithRandomPassword($validator->validated());

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'message' => 'Registration successful',
                    'user' => [
                        'id' => $result['user']->id,
                        'name' => $result['user']->name,
                        'email' => $result['user']->email,
                        'registration_number' => $result['user']->registration_number,
                        'xendit_invoice_url' => $result['invoice_url']
                    ]
                ], 201);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $result['message']
                ], 400);
            }

        } catch (\Exception $e) {
            Log::error('API registration failed', [
                'error' => $e->getMessage(),
                'request' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Internal server error'
            ], 500);
        }
    }
}