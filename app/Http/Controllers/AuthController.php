<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\JerseySize;
use App\Models\RaceCategory;
use App\Models\BloodType;
use App\Models\EventSource;
use App\Services\XenditService;
use App\Services\RandomPasswordService;
use App\Services\RecaptchaService;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    private $xenditService;
    private $randomPasswordService;
    private $recaptchaService;
    private $whatsappService;

    public function __construct(
        XenditService $xenditService,
        RandomPasswordService $randomPasswordService,
        RecaptchaService $recaptchaService,
        WhatsAppService $whatsappService
    ) {
        $this->xenditService = $xenditService;
        $this->randomPasswordService = $randomPasswordService;
        $this->recaptchaService = $recaptchaService;
        $this->whatsappService = $whatsappService;
    }
    public function showRegister()
    {
        $jerseySizes = JerseySize::active()->get();
        $raceCategories = RaceCategory::active()->get();
        $bloodTypes = BloodType::active()->get();
        $eventSources = EventSource::active()->get();

        return view('auth.register', compact('jerseySizes', 'raceCategories', 'bloodTypes', 'eventSources'));
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

        return view('auth.register-random-password', compact('jerseySizes', 'raceCategories', 'bloodTypes', 'eventSources'));
    }

    public function register(Request $request)
    {
        // Verify reCAPTCHA
        if ($request->has('g-recaptcha-response')) {
            $recaptchaResult = $this->recaptchaService->verify($request->input('g-recaptcha-response'));
            
            if (!$recaptchaResult['success']) {
                return redirect()->back()
                    ->withErrors(['recaptcha' => 'reCAPTCHA verification failed: ' . $recaptchaResult['message']])
                    ->withInput();
            }
        }

        $validationRules = [
            'name' => 'required|string|max:255',
            'bib_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:15',
            'gender' => 'required|in:Laki-laki,Perempuan',
            'birth_place' => 'required|string|max:255',
            'birth_date' => 'required|date|before:today',
            'address' => 'required|string|max:500',
            'regency_id' => 'required|string|max:255',
            'regency_name' => 'required|string|max:255',
            'province_name' => 'required|string|max:255',
            'jersey_size' => 'required|string|max:10',
            'race_category' => 'required|string|max:255',
            'whatsapp_number' => 'required|string|max:15',
            'emergency_contact_name' => 'required|string|max:255',
            'emergency_contact_phone' => 'required|string|max:20|regex:/^[0-9+\-\s]+$/',
            'group_community' => 'nullable|string|max:255',
            'blood_type' => 'required|string|max:5',
            'occupation' => 'required|string|max:255',
            'medical_history' => 'nullable|string|max:1000',
            'event_source' => 'required|string|max:255',
        ];

        $validator = Validator::make($request->all(), $validationRules);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Format WhatsApp number
        $whatsappNumber = $this->formatPhoneNumber($request->whatsapp_number);
        
        // Validate WhatsApp number (optional - can be disabled if API is down)
        if (config('app.validate_whatsapp', true)) {
            try {
                $validationResult = $this->validateWhatsAppNumber($whatsappNumber);
                
                if ($validationResult['success'] && !$validationResult['valid']) {
                    return redirect()->back()
                        ->withErrors(['whatsapp_number' => 'Nomor WhatsApp tidak valid atau tidak terdaftar'])
                        ->withInput();
                }
                
                if (!$validationResult['success']) {
                    // Log warning but continue with registration
                    Log::warning('WhatsApp validation service unavailable', [
                        'number' => $whatsappNumber,
                        'error' => $validationResult['message']
                    ]);
                }
            } catch (\Exception $e) {
                // Log the error but don't block registration
                Log::warning('WhatsApp validation failed during registration', [
                    'number' => $whatsappNumber,
                    'error' => $e->getMessage()
                ]);
            }
        }

        // Use temporary password initially
        $temporaryPassword = 'temp_password_' . time();

        // Generate unique registration number
        $registrationNumber = $this->generateRegistrationNumber();

        // Get and validate ticket type
        $ticketType = \App\Models\TicketType::getCurrentTicketType($request->race_category);
        
        // If not found using getCurrentTicketType, try manual search
        if (!$ticketType) {
            $ticketType = \App\Models\TicketType::whereHas('raceCategory', function($query) use ($request) {
                $query->where('name', $request->race_category);
            })->where('is_active', true)->first();
        }

        // Validate ticket availability and quota
        if (!$ticketType) {
            return redirect()->back()
                ->withErrors(['race_category' => 'No tickets available for this category'])
                ->withInput();
        }

        // Check if ticket type is currently active (includes quota check)
        if (!$ticketType->isCurrentlyActive()) {
            if ($ticketType->isQuotaExceeded()) {
                return redirect()->back()
                    ->withErrors(['race_category' => 'Registration quota for this category has been exceeded'])
                    ->withInput();
            } else {
                return redirect()->back()
                    ->withErrors(['race_category' => 'Registration period for this category is not active'])
                    ->withInput();
            }
        }

        Log::info('Ticket type selected for web registration', [
            'race_category' => $request->race_category,
            'ticket_type' => [
                'id' => $ticketType->id,
                'name' => $ticketType->name,
                'price' => $ticketType->price,
                'quota' => $ticketType->quota,
                'registered_count' => $ticketType->registered_count,
                'remaining_quota' => $ticketType->getRemainingQuota()
            ]
        ]);

        $user = User::create([
            'name' => $request->name,
            'bib_name' => $request->bib_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($temporaryPassword),
            'role' => 'user', // Default role for new registrations
            'status' => 'pending',
            'registration_number' => $registrationNumber,
            'is_active' => true,
            'payment_status' => 'pending',
            'ticket_type_id' => $ticketType->id, // Add ticket type ID
            'gender' => $request->gender,
            'birth_place' => $request->birth_place,
            'birth_date' => $request->birth_date,
            'address' => $request->address,
            'regency_id' => $request->regency_id,
            'regency_name' => $request->regency_name,
            'province_name' => $request->province_name,
            'jersey_size' => $request->jersey_size,
            'race_category' => $request->race_category,
            'whatsapp_number' => $whatsappNumber, // Use formatted number
            'emergency_contact_name' => $request->emergency_contact_name,
            'emergency_contact_phone' => $request->emergency_contact_phone,
            'group_community' => $request->group_community,
            'blood_type' => $request->blood_type,
            'occupation' => $request->occupation,
            'medical_history' => $request->medical_history,
            'event_source' => $request->event_source,
        ]);

        // Generate and send random password (always simple type)
        $passwordResult = $this->randomPasswordService->generateAndSendPassword(
            $user, 
            $this->whatsappService, 
            'simple'
        );

        if (!$passwordResult['success']) {
            // Delete user if password generation failed
            $user->delete();
            
            return redirect()->back()
                ->withErrors(['password' => $passwordResult['message']])
                ->withInput();
        }

        Log::info('User registered with random password', [
            'user_id' => $user->id,
            'email' => $user->email,
            'password_sent' => $passwordResult['password_sent'],
            'password_type' => 'simple'
        ]);

        // Increment registered count for ticket type
        $ticketType->increment('registered_count');

        // Send WhatsApp activation message and verify account automatically
        $activationResult = $this->sendActivationMessage($user);
        
        if ($activationResult['success']) {
            // Automatically verify WhatsApp if message sent successfully
            $user->update([
                'whatsapp_verified' => true,
                'whatsapp_verified_at' => now(),
                'status' => 'verified' // Change status to verified
            ]);
            
            Log::info('User registered and WhatsApp verified automatically', [
                'user_id' => $user->id,
                'email' => $user->email,
                'whatsapp' => $user->whatsapp_number
            ]);
        } else {
            Log::warning('User registered but WhatsApp activation failed', [
                'user_id' => $user->id,
                'email' => $user->email,
                'whatsapp' => $user->whatsapp_number,
                'error' => $activationResult['message']
            ]);
        }

        // Create Xendit payment invoice
        $paymentResult = $this->xenditService->createInvoice(
            $user, 
            null, // Let the service use the user's race category price
            'Amazing Sultra Run Registration Fee - ' . $user->name
        );

        if ($paymentResult['success']) {
            // Send payment link via WhatsApp
            $this->sendPaymentLink($user, $paymentResult['invoice_url']);
            
            return redirect()->route('login')->with('success', 
                'Registrasi berhasil! Link pembayaran telah dikirim ke WhatsApp Anda. Silakan lakukan pembayaran untuk mengaktifkan membership.'
            );
        } else {
            Log::error('Failed to create payment invoice', [
                'user_id' => $user->id,
                'error' => $paymentResult['error']
            ]);
            
            return redirect()->route('login')->with('warning', 
                'Registrasi berhasil! Namun link pembayaran gagal dibuat. Silakan hubungi admin untuk bantuan pembayaran.'
            );
        }
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function verifyWhatsapp(Request $request)
    {
        $userId = $request->input('user_id');
        $user = User::find($userId);

        if ($user) {
            $user->update([
                'whatsapp_verified' => true,
                'whatsapp_verified_at' => now(),
                'status' => 'verified'
            ]);

            return response()->json(['message' => 'WhatsApp verified successfully']);
        }

        return response()->json(['message' => 'User not found'], 404);
    }

    public function confirmPayment(Request $request)
    {
        $userId = $request->input('user_id');
        $amount = $request->input('amount', 100000); // Default amount
        $method = $request->input('method', 'whatsapp');
        $notes = $request->input('notes', '');

        $user = User::find($userId);

        if ($user) {
            $user->update([
                'payment_confirmed' => true,
                'payment_confirmed_at' => now(),
                'payment_amount' => $amount,
                'payment_method' => $method,
                'payment_notes' => $notes,
                'status' => 'paid'
            ]);

            return response()->json(['message' => 'Payment confirmed successfully']);
        }

        return response()->json(['message' => 'User not found'], 404);
    }

    /**
     * Register with random password and reCAPTCHA
     */
    public function registerWithRandomPassword(Request $request)
    {
        // Verify reCAPTCHA
        $recaptchaResult = $this->recaptchaService->verify($request->input('g-recaptcha-response'));
        
        if (!$recaptchaResult['success']) {
            return redirect()->back()
                ->withErrors(['recaptcha' => 'reCAPTCHA verification failed: ' . $recaptchaResult['message']])
                ->withInput();
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'bib_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:15',
            'gender' => 'required|in:Laki-laki,Perempuan',
            'birth_place' => 'required|string|max:255',
            'birth_date' => 'required|date|before:today',
            'address' => 'required|string|max:500',
            'jersey_size' => 'required|string|max:10',
            'race_category' => 'required|string|max:255',
            'whatsapp_number' => 'required|string|max:15',
            'emergency_contact_name' => 'required|string|max:255',
            'emergency_contact_phone' => 'required|string|max:20|regex:/^[0-9+\-\s]+$/',
            'group_community' => 'nullable|string|max:255',
            'blood_type' => 'required|string|max:5',
            'occupation' => 'required|string|max:255',
            'medical_history' => 'nullable|string|max:1000',
            'event_source' => 'required|string|max:255',
            'password_type' => 'required|in:simple,complex,memorable',
            'g-recaptcha-response' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Format WhatsApp number
        $whatsappNumber = $this->formatPhoneNumber($request->whatsapp_number);
        
        // Validate WhatsApp number (optional - can be disabled if API is down)
        if (config('app.validate_whatsapp', true)) {
            try {
                $validationResult = $this->validateWhatsAppNumber($whatsappNumber);
                
                if ($validationResult['success'] && !$validationResult['valid']) {
                    return redirect()->back()
                        ->withErrors(['whatsapp_number' => 'Nomor WhatsApp tidak valid atau tidak terdaftar'])
                        ->withInput();
                }
                
                if (!$validationResult['success']) {
                    // Log warning but continue with registration
                    Log::warning('WhatsApp validation service unavailable', [
                        'number' => $whatsappNumber,
                        'error' => $validationResult['message']
                    ]);
                }
            } catch (\Exception $e) {
                // Log the error but don't block registration
                Log::warning('WhatsApp validation failed during registration', [
                    'number' => $whatsappNumber,
                    'error' => $e->getMessage()
                ]);
            }
        }

        // Create user with temporary password
        $user = User::create([
            'name' => $request->name,
            'bib_name' => $request->bib_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make('temp_password_' . time()), // Temporary password
            'gender' => $request->gender,
            'birth_place' => $request->birth_place,
            'birth_date' => $request->birth_date,
            'address' => $request->address,
            'jersey_size' => $request->jersey_size,
            'race_category' => $request->race_category,
            'whatsapp_number' => $whatsappNumber,
            'emergency_contact_name' => $request->emergency_contact_name,
            'emergency_contact_phone' => $request->emergency_contact_phone,
            'group_community' => $request->group_community,
            'blood_type' => $request->blood_type,
            'occupation' => $request->occupation,
            'medical_history' => $request->medical_history,
            'event_source' => $request->event_source,
            'status' => 'pending',
            'payment_amount' => $this->getRegistrationFee($request->race_category),
        ]);

        // Generate and send random password
        $passwordType = $request->input('password_type', 'simple');
        $passwordResult = $this->randomPasswordService->generateAndSendPassword(
            $user, 
            $this->whatsappService, 
            $passwordType
        );

        if ($passwordResult['success']) {
            // Generate Xendit external_id
            $externalId = 'AMAZING-REG-' . $user->id . '-' . time();
            $user->update(['xendit_external_id' => $externalId]);

            // Create Xendit invoice
            try {
                $invoice = $this->xenditService->createInvoice($user);
                $user->update([
                    'xendit_invoice_id' => $invoice['id'],
                    'xendit_invoice_url' => $invoice['invoice_url'],
                    'xendit_callback_data' => $invoice,
                    'status' => 'registered'
                ]);

                Log::info('User registered with random password', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'password_sent' => $passwordResult['password_sent'],
                    'recaptcha_score' => $recaptchaResult['score'] ?? null
                ]);

                return redirect()->route('payment.success', ['user' => $user->id])
                    ->with('success', 'Registrasi berhasil! Password login telah dikirim ke WhatsApp Anda.');

            } catch (\Exception $e) {
                Log::error('Xendit invoice creation failed', [
                    'user_id' => $user->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);

                return redirect()->back()
                    ->withErrors(['payment' => 'Registrasi berhasil tetapi gagal membuat invoice pembayaran. Silakan hubungi admin.'])
                    ->withInput();
            }
        } else {
            // Delete user if password generation failed
            $user->delete();
            
            return redirect()->back()
                ->withErrors(['password' => $passwordResult['message']])
                ->withInput();
        }
    }

    private function sendActivationMessage($user)
    {
        try {
            // Create activation message
            $message = "🎉 *SELAMAT DATANG di Amazing Sultra Run!* 🎉\n\n";
            $message .= "Halo *{$user->name}*!\n\n";
            $message .= "Terima kasih telah mendaftar untuk event Amazing Sultra Run!\n\n";
            $message .= "📋 *Data Registrasi Anda:*\n";
            $message .= "• Nama: {$user->name}\n";
            $message .= "• Nama BIB: {$user->bib_name}\n";
            $message .= "• Email: {$user->email}\n";
            $message .= "• Kategori: {$user->race_category}\n";
            $message .= "• Ukuran Jersey: {$user->jersey_size}\n";
            $message .= "• WhatsApp: {$user->whatsapp_number}\n\n";
            $message .= "✅ *Akun Anda telah AKTIF!*\n";
            $message .= "Anda sekarang bisa login ke sistem menggunakan:\n";
            $message .= "• Email: {$user->email}\n";
            $message .= "• Password: (yang telah dikirim sebelumnya)\n\n";
            $message .= "🌐 Link Login: " . url('/login') . "\n\n";
            $message .= "📱 Untuk informasi lebih lanjut atau bantuan, silakan hubungi admin.\n\n";
            $message .= "Terima kasih dan selamat berlari! 🏃‍♂️🏃‍♀️\n\n";
            $message .= "_Tim Amazing Sultra Run_";

            // Use WhatsApp queue for better performance
            $queueId = $this->whatsappService->queueMessage($user->whatsapp_number, $message, 'high');

            if ($queueId) {
                Log::info('Activation message queued successfully', [
                    'user_id' => $user->id,
                    'whatsapp_number' => $user->whatsapp_number,
                    'queue_id' => $queueId
                ]);
                
                return [
                    'success' => true,
                    'message' => 'Pesan aktivasi sedang dikirim'
                ];
            } else {
                Log::error('Failed to queue activation message', [
                    'user_id' => $user->id,
                    'whatsapp_number' => $user->whatsapp_number
                ]);
                
                return [
                    'success' => false,
                    'message' => 'Gagal mengirim pesan aktivasi'
                ];
            }
        } catch (\Exception $e) {
            Log::error('Exception in sendActivationMessage', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return [
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengirim pesan aktivasi: ' . $e->getMessage()
            ];
        }
    }

    /**
     * API registration endpoint
     */
    public function registerApi(Request $request)
    {
        try {
            // Debug: log the request
            \Log::info('API Registration started', [
                'request_data' => $request->all(),
                'method' => $request->method(),
                'url' => $request->url()
            ]);
            
            // Validate the request
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'phone' => 'required|string|min:9|max:15',
                'category' => 'required|in:5K,10K,21K',
                'bib_name' => 'nullable|string|max:255',
                'gender' => 'nullable|in:Laki-laki,Perempuan',
                'birth_place' => 'nullable|string|max:255',
                'birth_date' => 'nullable|date',
                'address' => 'nullable|string|max:500',
                'jersey_size' => 'nullable|in:XS,S,M,L,XL,XXL',
                'whatsapp_number' => 'nullable|string|min:9|max:15',
                'emergency_contact_name' => 'nullable|string|max:255',
                'emergency_contact_phone' => 'nullable|string|min:9|max:15',
                'group_community' => 'nullable|string|max:255',
                'blood_type' => 'nullable|in:A,B,AB,O',
                'occupation' => 'nullable|string|max:255',
                'medical_history' => 'nullable|string|max:500',
                'event_source' => 'nullable|string|max:255',
                'regency_id' => 'nullable|string',
                'regency_name' => 'nullable|string|max:255',
                'province_name' => 'nullable|string|max:255'
            ]);

            Log::info('API Registration validation passed', [
                'validated_data' => $validatedData,
                'ip' => $request->ip()
            ]);

            // Format phone numbers
            $phoneNumber = $this->formatPhoneNumber($request->phone);
            $whatsappNumber = $request->whatsapp_number ? $this->formatPhoneNumber($request->whatsapp_number) : $phoneNumber;
            
            // Get and validate ticket type with quota check
            $ticketType = \App\Models\TicketType::getCurrentTicketType($request->category);
            
            // If not found using getCurrentTicketType, try manual search
            if (!$ticketType) {
                $ticketType = \App\Models\TicketType::whereHas('raceCategory', function($query) use ($request) {
                    $query->where('name', $request->category);
                })->where('is_active', true)->first();
            }

            // Validate ticket availability and quota
            if (!$ticketType) {
                return response()->json([
                    'success' => false,
                    'message' => "Kategori '{$request->category}' tidak tersedia. Silakan pilih kategori lain atau hubungi admin.",
                    'available_categories' => $this->getAvailableCategories()
                ], 400);
            }

            // Check if ticket type is currently active (includes quota check)
            if (!$ticketType->isCurrentlyActive()) {
                if ($ticketType->isQuotaExceeded()) {
                    return response()->json([
                        'success' => false,
                        'message' => "Quota untuk kategori '{$request->category}' sudah habis. Silakan pilih kategori lain yang masih tersedia.",
                        'quota_info' => [
                            'category' => $request->category,
                            'quota' => $ticketType->quota,
                            'registered' => $ticketType->registered_count,
                            'status' => 'quota_exceeded'
                        ],
                        'available_categories' => $this->getAvailableCategories()
                    ], 400);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => "Pendaftaran untuk kategori '{$request->category}' belum dibuka atau sudah ditutup.",
                        'period_info' => [
                            'category' => $request->category,
                            'start_date' => $ticketType->start_date,
                            'end_date' => $ticketType->end_date,
                            'status' => 'period_inactive'
                        ],
                        'available_categories' => $this->getAvailableCategories()
                    ], 400);
                }
            }

            // Generate unique registration number
            $registrationNumber = $this->generateRegistrationNumber();
            
            // Create user with complete registration data
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $phoneNumber,
                'password' => Hash::make('temp_password_' . time()),
                'role' => 'user',
                'status' => 'pending',
                'registration_number' => $registrationNumber,
                'is_active' => true,
                'payment_status' => 'pending',
                'race_category' => $request->category,
                'ticket_type_id' => $ticketType->id,
                'whatsapp_number' => $whatsappNumber,
                // Location fields
                'regency_id' => $request->regency_id,
                'regency_name' => $request->regency_name,
                'province_name' => $request->province_name,
                // Personal information from request
                'gender' => $request->gender,
                'birth_place' => $request->birth_place,
                'birth_date' => $request->birth_date,
                'address' => $request->address,
                'jersey_size' => $request->jersey_size,
                'emergency_contact_name' => $request->emergency_contact_name,
                'emergency_contact_phone' => $request->emergency_contact_phone ? $this->formatPhoneNumber($request->emergency_contact_phone) : 'To be updated',
                'blood_type' => $request->blood_type,
                'occupation' => $request->occupation,
                'group_community' => $request->group_community,
                'medical_history' => $request->medical_history,
                'event_source' => $request->event_source,
                'bib_name' => $request->bib_name,
            ]);

            // Increment registered count for ticket type
            $ticketType->increment('registered_count');

            Log::info('User created successfully', [
                'user_id' => $user->id,
                'email' => $user->email,
                'registration_number' => $user->registration_number,
                'ticket_type_id' => $ticketType->id,
                'category' => $request->category
            ]);

            // Generate and send random password
            try {
                $passwordResult = $this->randomPasswordService->generateAndSendPassword(
                    $user, 
                    $this->whatsappService, 
                    'simple'
                );
                
                if ($passwordResult['success']) {
                    Log::info('Password sent successfully', [
                        'user_id' => $user->id,
                        'password_sent' => $passwordResult['password_sent']
                    ]);
                }
            } catch (\Exception $e) {
                Log::warning('Password generation failed', [
                    'user_id' => $user->id,
                    'error' => $e->getMessage()
                ]);
            }

            // Send activation message
            try {
                $activationResult = $this->sendActivationMessage($user);
                
                if ($activationResult['success']) {
                    $user->update([
                        'whatsapp_verified' => true,
                        'whatsapp_verified_at' => now(),
                        'status' => 'verified'
                    ]);
                }
            } catch (\Exception $e) {
                Log::warning('Activation message failed', [
                    'user_id' => $user->id,
                    'error' => $e->getMessage()
                ]);
            }

            // Create Xendit payment invoice
            try {
                $paymentResult = $this->xenditService->createInvoice(
                    $user, 
                    null, // Use race category price
                    'Amazing Sultra Run Registration Fee - ' . $user->name
                );

                if ($paymentResult['success']) {
                    // Send payment link
                    $this->sendPaymentLink($user, $paymentResult['invoice_url']);
                    
                    return response()->json([
                        'success' => true,
                        'message' => 'Registration successful! Check your WhatsApp for login details and payment link.',
                        'data' => [
                            'registration_number' => $user->registration_number,
                            'payment_url' => $paymentResult['invoice_url']
                        ]
                    ]);
                } else {
                    Log::error('Payment invoice creation failed', [
                        'user_id' => $user->id,
                        'error' => $paymentResult['error']
                    ]);
                    
                    return response()->json([
                        'success' => true,
                        'message' => 'Registration successful! However, payment link could not be created. Please contact admin.',
                        'data' => [
                            'registration_number' => $user->registration_number
                        ]
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('Payment service failed', [
                    'user_id' => $user->id,
                    'error' => $e->getMessage()
                ]);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Registration successful! However, some services are unavailable. Please contact admin.',
                    'data' => [
                        'registration_number' => $user->registration_number
                    ]
                ]);
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('API Registration validation failed', [
                'errors' => $e->errors(),
                'input' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
            
        } catch (\Exception $e) {
            Log::error('API Registration error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'input' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Registration failed. Please try again.'
            ], 500);
        }
    }

    /**
     * Debug API Registration
     */
    public function debugRegisterApi(Request $request)
    {
        try {
            return response()->json([
                'success' => true,
                'message' => 'Debug API works!',
                'data' => [
                    'request_method' => $request->method(),
                    'request_data' => $request->all(),
                    'headers' => $request->headers->all()
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Debug API failed: ' . $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

    /**
     * Generate unique registration number
     */
    private function generateRegistrationNumber()
    {
        $prefix = 'ASR' . date('Y'); // ASR2025
        $counter = 1;
        
        // Get the last registration number for this year
        $lastUser = User::where('registration_number', 'like', $prefix . '%')
            ->orderBy('registration_number', 'desc')
            ->first();
        
        if ($lastUser) {
            $lastNumber = intval(substr($lastUser->registration_number, -4));
            $counter = $lastNumber + 1;
        }
        
        return $prefix . sprintf('%04d', $counter);
    }

    private function sendPaymentLink($user, $invoiceUrl)
    {
        try {
            $amount = number_format($user->registration_fee, 0, ',', '.');
            
            $message = "🎯 *LINK PEMBAYARAN REGISTRASI* 🎯\n\n";
            $message .= "Halo *{$user->name}*,\n\n";
            $message .= "Terima kasih telah mendaftar di Amazing Sultra Run! 🏃‍♂️\n\n";
            $message .= "📋 *Detail Pembayaran:*\n";
            $message .= "• Kategori: {$user->race_category}\n";
            $message .= "• Biaya Registrasi: Rp {$amount}\n";
            $message .= "• Berlaku selama: 24 jam\n\n";
            $message .= "💳 *Link Pembayaran:*\n";
            $message .= "{$invoiceUrl}\n\n";
            $message .= "⚠️ *Penting:*\n";
            $message .= "• Link pembayaran akan kedaluwarsa dalam 24 jam\n";
            $message .= "• Anda akan mendapat konfirmasi otomatis setelah pembayaran berhasil\n";
            $message .= "• Membership akan aktif setelah pembayaran terkonfirmasi\n\n";
            $message .= "📞 Butuh bantuan? Hubungi: +62811-4000-805\n\n";
            $message .= "Terima kasih! 🙏";

            // Use WhatsApp queue for better performance
            $queueId = $this->whatsappService->queueMessage($user->whatsapp_number, $message, 'high');

            if ($queueId) {
                Log::info('Payment link queued successfully via WhatsApp', [
                    'user_id' => $user->id,
                    'whatsapp_number' => $user->whatsapp_number,
                    'invoice_url' => $invoiceUrl,
                    'queue_id' => $queueId
                ]);
                return true;
            } else {
                Log::error('Failed to queue payment link via WhatsApp', [
                    'user_id' => $user->id,
                    'whatsapp_number' => $user->whatsapp_number,
                    'invoice_url' => $invoiceUrl
                ]);
                return false;
            }
        } catch (\Exception $e) {
            Log::error('Exception sending payment link via WhatsApp', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'invoice_url' => $invoiceUrl
            ]);
            return false;
        }
    }

    /**
     * Validate WhatsApp number via AJAX
     */
    public function validateWhatsAppAjax(Request $request)
    {
        try {
            $whatsappNumber = $request->input('whatsapp_number');
            
            if (!$whatsappNumber) {
                return response()->json([
                    'success' => false,
                    'valid' => false,
                    'message' => 'Nomor WhatsApp diperlukan'
                ]);
            }

            // Format phone number
            $formattedNumber = $this->formatPhoneNumber($whatsappNumber);
            
            // Check if WhatsApp validation is enabled
            if (!config('app.validate_whatsapp', true)) {
                return response()->json([
                    'success' => true,
                    'valid' => true,
                    'message' => 'Validasi WhatsApp dilewati (disabled)'
                ]);
            }
            
            // Validate WhatsApp number with timeout protection
            $validationResult = $this->validateWhatsAppNumber($formattedNumber);
            
            // Only allow fallback for connection/timeout errors (success=false)
            // If API responds with invalid number (success=true, valid=false), block registration
            if (!$validationResult['success']) {
                // True service error - timeout, connection failed, etc.
                Log::warning('WhatsApp validation service failed, allowing registration', [
                    'number' => $formattedNumber,
                    'error' => $validationResult['message']
                ]);
                
                return response()->json([
                    'success' => true,
                    'valid' => true,
                    'message' => 'Nomor WhatsApp diterima (validasi service tidak tersedia)'
                ]);
            }
            
            // API responded successfully - return the actual validation result
            return response()->json($validationResult);
            
        } catch (\Exception $e) {
            Log::error('WhatsApp validation AJAX error', [
                'error' => $e->getMessage(),
                'number' => $request->input('whatsapp_number')
            ]);
            
            // For exceptions, block registration to be safe
            return response()->json([
                'success' => false,
                'valid' => false,
                'message' => 'Validasi WhatsApp gagal. Silakan coba lagi atau hubungi admin.'
            ]);
        }
    }

    /**
     * Get registration fee based on race category
     */
    private function getRegistrationFee($raceCategoryName)
    {
        $raceCategory = RaceCategory::where('name', $raceCategoryName)->first();
        if ($raceCategory) {
            return (float) $raceCategory->price;
        }
        return (float) config('xendit.registration_fee', 150000); // Default fallback
    }

    /**
     * Simple API Registration (no dependencies)
     */
    public function registerApiSimple(Request $request)
    {
        try {
            // Validate the request with all possible fields
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'phone' => 'required|string|min:9|max:15',
                'category' => 'required|in:5K,10K,21K',
                // Optional detailed fields
                'birth_place' => 'nullable|string|max:255',
                'birth_date' => 'nullable|date|before:today',
                'address' => 'nullable|string|max:500',
                'regency_id' => 'nullable|string|max:255',
                'regency_name' => 'nullable|string|max:255',
                'province_name' => 'nullable|string|max:255',
                'gender' => 'nullable|in:Laki-laki,Perempuan',
                'jersey_size' => 'nullable|string|max:10',
                'emergency_contact_name' => 'nullable|string|max:255',
                'emergency_contact_phone' => 'nullable|string|max:20',
                'group_community' => 'nullable|string|max:255',
                'blood_type' => 'nullable|string|max:5',
                'occupation' => 'nullable|string|max:255',
                'medical_history' => 'nullable|string|max:1000',
                'event_source' => 'nullable|string|max:255',
                'bib_name' => 'nullable|string|max:255'
            ]);

            // Generate unique registration number
            $registrationNumber = $this->generateRegistrationNumber();
            
            // Format phone number  
            $phoneNumber = $this->formatPhoneNumber($request->phone);

            // Get ticket type ID based on category
            // First try to find by name matching category
            $ticketType = \App\Models\TicketType::where('name', $request->category)->first();
            
            // If not found, try to find by race_category relationship
            if (!$ticketType) {
                $ticketType = \App\Models\TicketType::whereHas('raceCategory', function($query) use ($request) {
                    $query->where('name', $request->category);
                })->first();
            }
            
            // If still not found, get the first active ticket type
            if (!$ticketType) {
                $ticketType = \App\Models\TicketType::where('is_active', true)->first();
            }
            
            $ticketTypeId = $ticketType ? $ticketType->id : null;

            // Create user with all provided data or defaults
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $phoneNumber,
                'password' => Hash::make('temp_password_' . time()),
                'role' => 'user',
                'status' => 'pending',
                'registration_number' => $registrationNumber,
                'is_active' => true,
                'payment_status' => 'pending',
                'race_category' => $request->category,
                'ticket_type_id' => $ticketTypeId,
                'whatsapp_number' => $phoneNumber,
                // Use provided data or defaults
                'gender' => $request->gender ?: 'Laki-laki',
                'birth_place' => $request->birth_place ?: 'Unknown',
                'birth_date' => $request->birth_date ?: '1990-01-01',
                'address' => $request->address ?: 'To be updated',
                'regency_id' => $request->regency_id,
                'regency_name' => $request->regency_name,
                'province_name' => $request->province_name,
                'jersey_size' => $request->jersey_size ?: 'M',
                'emergency_contact_name' => $request->emergency_contact_name ?: 'To be updated',
                'emergency_contact_phone' => $request->emergency_contact_phone ?: 'To be updated',
                'blood_type' => $request->blood_type ?: 'O',
                'occupation' => $request->occupation ?: 'Unknown',
                'group_community' => $request->group_community,
                'medical_history' => $request->medical_history,
                'event_source' => $request->event_source ?: 'Website',
                'bib_name' => $request->bib_name ?: $request->name,
            ]);

            // Update registered count in ticket_types table
            if ($ticketType) {
                $ticketType->increment('registered_count');
            }

            // Generate and send password via WhatsApp
            try {
                $tempPassword = 'ASR' . rand(1000, 9999);
                $user->update(['password' => Hash::make($tempPassword)]);

                // Try to send WhatsApp message with login credentials
                $whatsappSent = false;
                try {
                    $whatsappSent = $this->sendWelcomeWhatsAppMessage($user, $tempPassword);
                } catch (\Exception $e) {
                    Log::warning('WhatsApp sending failed', [
                        'user_id' => $user->id,
                        'error' => $e->getMessage()
                    ]);
                }
                
                Log::info('Registration completed successfully', [
                    'user_id' => $user->id,
                    'registration_number' => $user->registration_number,
                    'ticket_type_id' => $ticketTypeId,
                    'whatsapp_sent' => $whatsappSent
                ]);
            } catch (\Exception $e) {
                Log::warning('Post-registration processing failed', [
                    'user_id' => $user->id,
                    'error' => $e->getMessage()
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Registration successful! Login credentials will be sent to your WhatsApp if available.',
                'data' => [
                    'registration_number' => $user->registration_number,
                    'user_id' => $user->id,
                    'ticket_type' => $request->category,
                    'ticket_type_id' => $ticketTypeId
                ]
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Registration failed: ' . $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

    /**
     * Ultra Simple API Registration (no WhatsApp)
     */
    public function registerApiUltraSimple(Request $request)
    {
        try {
            // Validate the request with all required fields
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'phone' => 'required|string|min:9|max:15',
                'category' => 'required|in:5K,10K,21K',
                // Optional detailed fields
                'birth_place' => 'nullable|string|max:255',
                'birth_date' => 'nullable|date|before:today',
                'address' => 'nullable|string|max:500',
                'regency_id' => 'nullable|string|max:255',
                'regency_name' => 'nullable|string|max:255',
                'province_name' => 'nullable|string|max:255',
                'gender' => 'nullable|in:Laki-laki,Perempuan',
                'jersey_size' => 'nullable|string|max:10',
                'emergency_contact_name' => 'nullable|string|max:255',
                'emergency_contact_phone' => 'nullable|string|max:20',
                'group_community' => 'nullable|string|max:255',
                'blood_type' => 'nullable|string|max:5',
                'occupation' => 'nullable|string|max:255',
                'medical_history' => 'nullable|string|max:1000',
                'event_source' => 'nullable|string|max:255',
                'bib_name' => 'nullable|string|max:255'
            ]);

            // Generate unique registration number
            $registrationNumber = $this->generateRegistrationNumber();
            
            // Format phone number  
            $phoneNumber = $this->formatPhoneNumber($request->phone);

            // Get ticket type based on category with quota validation
            $ticketType = \App\Models\TicketType::getCurrentTicketType($request->category);
            
            // If not found using getCurrentTicketType, try manual search
            if (!$ticketType) {
                $ticketType = \App\Models\TicketType::where('name', $request->category)
                    ->where('is_active', true)
                    ->first();
                
                // If not found, try to find by race_category relationship
                if (!$ticketType) {
                    $ticketType = \App\Models\TicketType::whereHas('raceCategory', function($query) use ($request) {
                        $query->where('name', $request->category);
                    })->where('is_active', true)->first();
                }
            }
            
            $ticketTypeId = $ticketType ? $ticketType->id : null;

            // Create user with all provided data or defaults
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $phoneNumber,
                'password' => Hash::make('temp_password_' . time()),
                'role' => 'user',
                'status' => 'pending',
                'registration_number' => $registrationNumber,
                'is_active' => true,
                'payment_status' => 'pending',
                'race_category' => $request->category,
                'ticket_type_id' => $ticketTypeId,
                'whatsapp_number' => $phoneNumber,
                // Use provided data or defaults
                'gender' => $request->gender ?: 'Laki-laki',
                'birth_place' => $request->birth_place ?: 'Unknown',
                'birth_date' => $request->birth_date ?: '1990-01-01',
                'address' => $request->address ?: 'To be updated',
                'regency_id' => $request->regency_id,
                'regency_name' => $request->regency_name,
                'province_name' => $request->province_name,
                'jersey_size' => $request->jersey_size ?: 'M',
                'emergency_contact_name' => $request->emergency_contact_name ?: 'To be updated',
                'emergency_contact_phone' => $request->emergency_contact_phone ?: 'To be updated',
                'blood_type' => $request->blood_type ?: 'O',
                'occupation' => $request->occupation ?: 'Unknown',
                'group_community' => $request->group_community,
                'medical_history' => $request->medical_history,
                'event_source' => $request->event_source ?: 'Website',
                'bib_name' => $request->bib_name ?: $request->name,
            ]);

            // Update registered count
            if ($ticketType) {
                $ticketType->increment('registered_count');
            }

            Log::info('Ultra simple registration completed', [
                'user_id' => $user->id,
                'registration_number' => $user->registration_number,
                'ticket_type_id' => $ticketTypeId
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Registration successful! Please contact admin for login credentials.',
                'data' => [
                    'registration_number' => $user->registration_number,
                    'user_id' => $user->id,
                    'ticket_type' => $request->category,
                    'ticket_type_id' => $ticketTypeId
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Ultra simple registration error', [
                'error' => $e->getMessage(),
                'input' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Registration failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Validate WhatsApp number via external API
     */
    private function validateWhatsAppNumber($phoneNumber)
    {
        try {
            // Format phone number to international format (remove + and spaces)
            $formattedNumber = preg_replace('/[^0-9]/', '', $phoneNumber);
            
            // Ensure it starts with 62 (Indonesia country code)
            if (!str_starts_with($formattedNumber, '62')) {
                if (str_starts_with($formattedNumber, '0')) {
                    $formattedNumber = '62' . substr($formattedNumber, 1);
                } else {
                    $formattedNumber = '62' . $formattedNumber;
                }
            }

            $apiKey = env('WHATSAPP_API_KEY', 'tZiKYy1sHXasOj0hDGZnRfAnAYo2Ec');
            $sender = env('WHATSAPP_SENDER', '628114040707');
            $apiUrl = 'https://wamd.system112.org/check-number';
            
            Log::info('Validating WhatsApp number', [
                'original' => $phoneNumber,
                'formatted' => $formattedNumber
            ]);

            // Set short timeout to prevent hanging (3 seconds)
            $response = Http::timeout(3)->get($apiUrl, [
                'api_key' => $apiKey,
                'sender' => $sender,
                'number' => $formattedNumber
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                Log::info('WhatsApp API response received', [
                    'phone' => $formattedNumber,
                    'response' => $data
                ]);
                
                // Check API response format
                // Success response: {"status": true, "data": {"jid": "...", "exists": true}}
                // Error response: {"status": false, "msg": "Failed to check number!,check your connection!"}
                
                if (isset($data['status']) && $data['status'] === true) {
                    // Check the data format - valid number should have array structure with exists=true
                    if (is_array($data['data']) && isset($data['data']['exists']) && $data['data']['exists'] === true) {
                        return [
                            'success' => true,
                            'valid' => true,
                            'message' => 'Nomor WhatsApp valid dan aktif'
                        ];
                    } else {
                        // Either data is not array structure or exists=false
                        // This handles cases like {"status":true,"data":true} which should be invalid
                        return [
                            'success' => true,
                            'valid' => false,
                            'message' => 'Nomor tidak terdaftar di WhatsApp'
                        ];
                    }
                } else {
                    // Status false means number not found in WhatsApp
                    $message = isset($data['msg']) ? $data['msg'] : 'Nomor tidak terdaftar di WhatsApp';
                    
                    return [
                        'success' => true,  // API berhasil, tapi nomor tidak valid
                        'valid' => false,
                        'message' => 'Nomor tidak terdaftar di WhatsApp'
                    ];
                }
            } else {
                // Check if this is a client error (4xx) with valid JSON response
                if ($response->status() >= 400 && $response->status() < 500) {
                    try {
                        $data = $response->json();
                        if (isset($data['status']) && $data['status'] === false) {
                            // This is a clear API response saying number is invalid
                            return [
                                'success' => true,
                                'valid' => false,
                                'message' => 'Nomor tidak terdaftar di WhatsApp'
                            ];
                        }
                    } catch (\Exception $e) {
                        // If JSON parsing fails, treat as service error
                    }
                }
                
                Log::warning('WhatsApp API request failed', [
                    'phone' => $formattedNumber,
                    'status_code' => $response->status()
                ]);
                
                return [
                    'success' => false,
                    'valid' => false,
                    'message' => 'Service WhatsApp tidak tersedia saat ini'
                ];
            }
            
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::warning('WhatsApp validation timeout', [
                'error' => $e->getMessage(),
                'number' => $phoneNumber
            ]);
            
            return [
                'success' => false,
                'valid' => false,
                'message' => 'Validasi WhatsApp timeout - service tidak responsif'
            ];
        } catch (\Exception $e) {
            Log::error('WhatsApp validation error', [
                'error' => $e->getMessage(),
                'number' => $phoneNumber
            ]);
            
            return [
                'success' => false,
                'valid' => false,
                'message' => 'Terjadi kesalahan saat validasi WhatsApp'
            ];
        }
    }

    /**
     * Check ticket availability for a race category
     */
    public function checkTicketAvailability(Request $request)
    {
        try {
            $category = $request->input('category');
            
            if (!$category) {
                return response()->json([
                    'success' => false,
                    'message' => 'Category is required'
                ], 400);
            }

            $ticketType = \App\Models\TicketType::getCurrentTicketType($category);
            
            if (!$ticketType) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tickets available for this category',
                    'data' => [
                        'available' => false,
                        'reason' => 'no_tickets'
                    ]
                ]);
            }

            if (!$ticketType->isCurrentlyActive()) {
                if ($ticketType->isQuotaExceeded()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Registration quota for this category has been exceeded',
                        'data' => [
                            'available' => false,
                            'reason' => 'quota_exceeded',
                            'quota' => $ticketType->quota,
                            'registered_count' => $ticketType->registered_count
                        ]
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Registration period for this category is not active',
                        'data' => [
                            'available' => false,
                            'reason' => 'period_inactive',
                            'start_date' => $ticketType->start_date,
                            'end_date' => $ticketType->end_date
                        ]
                    ]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Tickets are available for this category',
                'data' => [
                    'available' => true,
                    'ticket_type' => [
                        'id' => $ticketType->id,
                        'name' => $ticketType->name,
                        'price' => $ticketType->price,
                        'quota' => $ticketType->quota,
                        'registered_count' => $ticketType->registered_count,
                        'remaining_quota' => $ticketType->getRemainingQuota(),
                        'start_date' => $ticketType->start_date,
                        'end_date' => $ticketType->end_date
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error checking ticket availability: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Format phone number to international format
     */
    private function formatPhoneNumber($phoneNumber)
    {
        // Remove all non-numeric characters
        $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);
        
        // Handle Indonesian numbers
        if (substr($phoneNumber, 0, 1) === '0') {
            $phoneNumber = '62' . substr($phoneNumber, 1);
        } elseif (substr($phoneNumber, 0, 2) !== '62') {
            $phoneNumber = '62' . $phoneNumber;
        }
        
        return $phoneNumber;
    }

    /**
     * Get available categories for registration
     */
    private function getAvailableCategories()
    {
        try {
            $availableCategories = \App\Models\TicketType::where('is_active', true)
                ->whereRaw('registered_count < quota')
                ->whereDate('start_date', '<=', now())
                ->whereDate('end_date', '>=', now())
                ->with('raceCategory')
                ->get()
                ->map(function ($ticketType) {
                    return [
                        'category' => $ticketType->raceCategory->name ?? $ticketType->name,
                        'quota' => $ticketType->quota,
                        'registered' => $ticketType->registered_count,
                        'remaining' => $ticketType->quota - $ticketType->registered_count,
                        'price' => $ticketType->price,
                        'start_date' => $ticketType->start_date,
                        'end_date' => $ticketType->end_date
                    ];
                });

            return $availableCategories->toArray();
        } catch (\Exception $e) {
            Log::error('Error getting available categories', [
                'error' => $e->getMessage()
            ]);
            return [];
        }
    }
}
