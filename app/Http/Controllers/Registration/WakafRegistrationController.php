<?php

namespace App\Http\Controllers\Registration;

use App\Http\Controllers\Controller;
use App\Models\JerseySize;
use App\Models\RaceCategory;
use App\Models\BloodType;
use App\Models\EventSource;
use App\Models\TicketType;
use App\Services\Registration\WakafRegistrationService;
use App\Services\RecaptchaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class WakafRegistrationController extends Controller
{
    private $registrationService;
    private $recaptchaService;

    public function __construct(
        WakafRegistrationService $registrationService,
        RecaptchaService $recaptchaService
    ) {
        $this->registrationService = $registrationService;
        $this->recaptchaService = $recaptchaService;
    }

    /**
     * Show the Wakaf registration form
     */
    public function showWakafRegister()
    {
        $jerseySizes = JerseySize::active()->get();
        $raceCategories = RaceCategory::active()->get();
        $bloodTypes = BloodType::active()->get();
        $eventSources = EventSource::active()->get();
        
        // Get Wakaf ticket type
        $wakafTicketType = TicketType::where('name', 'LIKE', '%wakaf%')
            ->orWhere('id', 4)
            ->first();

        return view('auth.wakaf-register', compact(
            'jerseySizes', 
            'raceCategories', 
            'bloodTypes', 
            'eventSources',
            'wakafTicketType'
        ));
    }

    /**
     * Handle Wakaf registration
     */
    public function registerWakaf(Request $request)
    {
        // Validate reCAPTCHA
        $recaptchaResult = $this->recaptchaService->verify($request->input('g-recaptcha-response'));
        if (!$recaptchaResult['success']) {
            return back()
                ->withErrors(['recaptcha' => 'reCAPTCHA verification failed. Please try again.'])
                ->withInput();
        }

        // Validate Wakaf verification
        if (!$request->has('wakaf_verified') || !$request->wakaf_verified) {
            return back()
                ->withErrors(['wakaf_verified' => 'Wakaf verification is required before registration.'])
                ->withInput();
        }

        // Validate form data
        $validator = $this->getWakafRegistrationValidator($request->all());
        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        // Check Wakaf ticket availability
        $wakafTicketType = TicketType::where('name', 'LIKE', '%wakaf%')
            ->orWhere('id', 4)
            ->first();
            
        if (!$wakafTicketType) {
            return back()
                ->withErrors(['ticket_type' => 'Wakaf ticket type is not available.'])
                ->withInput();
        }

        if ($wakafTicketType->registered_count >= $wakafTicketType->quota) {
            return back()
                ->withErrors(['quota' => 'Wakaf ticket quota is full.'])
                ->withInput();
        }

        // Process Wakaf registration
        $data = $validator->validated();
        $data['wakaf_verified'] = true;
        $data['ticket_type_id'] = $wakafTicketType->id;

        $result = $this->registrationService->registerWakaf($data);

        if ($result['success']) {
            // Login user
            Auth::login($result['user']);
            
            // Store success data in session for wakaf success page
            session([
                'wakaf_success' => true,
                'wakaf_user' => $result['user'],
                'wakaf_invoice_url' => $result['invoice_url'],
                'wakaf_password_sent' => $result['password_sent']
            ]);

            Log::info('Wakaf registration successful redirect', [
                'user_id' => $result['user']->id,
                'invoice_url' => $result['invoice_url']
            ]);

            return redirect()->route('dashboard.invoice', ['id' => $result['user']->id])
                ->with('success', 'Registrasi Wakaf berhasil! Link pembayaran telah dibuat dan dikirim ke WhatsApp Anda.');

        } else {
            return back()
                ->withErrors(['registration' => $result['message']])
                ->withInput();
        }
    }

    /**
     * Verify SatuWakaf donation (AJAX endpoint)
     */
    public function verifySatuWakaf(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nama_wakif' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'no_hp' => 'required|string|max:20',
                'jumlah_wakaf' => 'required|numeric|min:10000'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $result = $this->registrationService->verifySatuWakafDonation($validator->validated());

            return response()->json($result);

        } catch (\Exception $e) {
            Log::error('SatuWakaf verification endpoint error', [
                'error' => $e->getMessage(),
                'request' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Internal server error'
            ], 500);
        }
    }

    /**
     * Check Wakaf registration status (for real-time monitoring)
     */
    public function checkWakafStatus(Request $request)
    {
        try {
            $email = $request->input('email');
            
            if (!$email) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email is required'
                ], 400);
            }

            // Here you would check the actual SatuWakaf API status
            // For now, return a mock response
            return response()->json([
                'success' => true,
                'status' => 'PENDING', // or 'VERIFIED'
                'message' => 'Wakaf donation is being processed'
            ]);

        } catch (\Exception $e) {
            Log::error('Wakaf status check error', [
                'error' => $e->getMessage(),
                'email' => $request->input('email')
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get Wakaf registration validator
     */
    private function getWakafRegistrationValidator(array $data): \Illuminate\Validation\Validator
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
            'event_source_id' => 'nullable|exists:event_sources,id',
            'wakaf_verified' => 'required|accepted',
            'terms_accepted' => 'required|accepted',
            
            // Wakaf-specific fields
            'wakaf_donation_id' => 'nullable|string',
            'nama_wakif' => 'required|string|max:255',
            'jumlah_wakaf' => 'required|numeric|min:10000'
        ];

        return Validator::make($data, $rules, [
            'wakaf_verified.required' => 'Wakaf verification is required.',
            'wakaf_verified.accepted' => 'You must complete wakaf verification first.',
            'jumlah_wakaf.min' => 'Minimum wakaf amount is Rp 10.000.',
            'nama_wakif.required' => 'Nama wakif (donor name) is required for wakaf registration.'
        ]);
    }
}