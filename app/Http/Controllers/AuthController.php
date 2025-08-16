<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\JerseySize;
use App\Models\RaceCategory;
use App\Models\BloodType;
use App\Models\EventSource;
use App\Models\TicketType;
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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

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

    /**
     * Show the collective registration form (multi-form for multiple users)
     */
    public function showRegisterKolektif()
    {
        try {
            // Use correct column names based on table structure
            $jerseySizes = JerseySize::select('id', 'name')->where('active', 1)->get();
            $raceCategories = RaceCategory::select('id', 'name')->where('active', 1)->get();
            $bloodTypes = BloodType::select('id', 'name')->where('active', 1)->get();
            $eventSources = EventSource::select('id', 'name')->where('active', 1)->get();

            // Debug info with counts
            \Log::info('Register Kolektif Raw Data:', [
                'jerseySizes_count' => $jerseySizes->count(),
                'raceCategories_count' => $raceCategories->count(),
                'bloodTypes_count' => $bloodTypes->count(),
                'eventSources_count' => $eventSources->count(),
                'jerseySizes' => $jerseySizes->toArray(),
                'raceCategories' => $raceCategories->toArray(),
                'bloodTypes' => $bloodTypes->toArray(),
                'eventSources' => $eventSources->toArray()
            ]);

            return view('auth.register-kolektif', compact('jerseySizes', 'raceCategories', 'bloodTypes', 'eventSources'));
        } catch (\Exception $e) {
            \Log::error('Error loading register kolektif data: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            // Fallback to empty arrays
            $jerseySizes = [];
            $raceCategories = [];
            $bloodTypes = [];
            $eventSources = [];
            
            return view('auth.register-kolektif', compact('jerseySizes', 'raceCategories', 'bloodTypes', 'eventSources'));
        }
    }

    /**
     * Show the wakaf registration form (only 5K category with wakaf ticket type)
     */
    public function showWakafRegister()
    {
        $jerseySizes = JerseySize::active()->get();
        $bloodTypes = BloodType::active()->get();
        $eventSources = EventSource::active()->get();
        
        // Get wakaf ticket type for 5K category
        $wakafTicketType = TicketType::where('name', 'wakaf')
            ->where('is_active', true)
            ->with('raceCategory')
            ->first();

        return view('auth.register-wakaf', compact('jerseySizes', 'bloodTypes', 'eventSources', 'wakafTicketType'));
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

        // Generate Xendit external_id for regular registration
        $externalId = 'AMAZING-REG-' . $user->id . '-' . time();
        $user->update(['xendit_external_id' => $externalId]);

        Log::info('Generated xendit_external_id for regular registration', [
            'user_id' => $user->id,
            'email' => $user->email,
            'xendit_external_id' => $externalId
        ]);

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
     * Send collective payment link to ALL participants
     */
    private function sendCollectivePaymentLinkToLeader($users, $invoiceUrl, $participantDetails, $totalAmount)
    {
        // ONLY send payment link to Group Leader (first participant - index 0)
        if (empty($users)) {
            Log::warning('No users provided for collective payment notification');
            return;
        }

        $groupLeader = $users[0]; // First participant is the group leader

        try {
            // Create participant list for the message
            $participantList = "";
            foreach ($participantDetails as $idx => $participant) {
                $participantList .= ($idx + 1) . ". " . $participant['name'] . " - " . $participant['category'] . " (Rp " . number_format($participant['fee'], 0, ',', '.') . ")\n";
            }

            $message = "🏃‍♂️ *AMAZING SULTRA RUN - PEMBAYARAN KOLEKTIF*\n\n";
            $message .= "Halo " . $groupLeader->name . " (Group Leader)! 👋\n\n";
            $message .= "Registrasi kolektif berhasil untuk " . count($participantDetails) . " peserta:\n\n";
            $message .= $participantList;
            $message .= "\n💰 *TOTAL PEMBAYARAN: Rp " . number_format($totalAmount, 0, ',', '.') . "*\n\n";
            $message .= "Silakan lakukan pembayaran melalui link berikut:\n";
            $message .= "🔗 " . $invoiceUrl . "\n\n";
            $message .= "ℹ️ *Petunjuk Pembayaran:*\n";
            $message .= "• Sebagai Group Leader, Anda bertanggung jawab untuk pembayaran kolektif\n";
            $message .= "• Link ini berlaku untuk SEMUA peserta dalam grup Anda\n";
            $message .= "• Jersey akan dikirim ke alamat Group Leader\n";
            $message .= "• Setelah pembayaran berhasil, SEMUA peserta akan otomatis terdaftar\n";
            $message .= "• Batas waktu pembayaran: 24 jam\n\n";
            $message .= "Anggota lain tidak akan menerima pesan invoice. Silakan koordinasikan pembayaran dengan tim Anda.\n\n";
            $message .= "Terima kasih! 🙏\n";
            $message .= "Tim Amazing Sultra Run";

            // Queue the message with high priority ONLY to Group Leader
            $queueId = $this->whatsappService->queueMessage($groupLeader->whatsapp_number, $message, 'high');

            Log::info('Collective payment link sent ONLY to Group Leader', [
                'group_leader_id' => $groupLeader->id,
                'group_leader_name' => $groupLeader->name,
                'group_leader_whatsapp' => $groupLeader->whatsapp_number,
                'total_participants' => count($users),
                'total_amount' => $totalAmount,
                'queue_id' => $queueId,
                'invoice_url' => $invoiceUrl,
                'policy' => 'ONLY_LEADER_RECEIVES_INVOICE'
            ]);

            // Log that other participants did NOT receive payment messages
            for ($i = 1; $i < count($users); $i++) {
                Log::info('Participant did NOT receive payment message (as intended)', [
                    'user_id' => $users[$i]->id,
                    'user_name' => $users[$i]->name,
                    'participant_index' => $i + 1,
                    'status' => 'PAYMENT_MESSAGE_SKIPPED_FOR_NON_LEADER'
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Failed to send collective payment link to Group Leader', [
                'group_leader_id' => $groupLeader->id,
                'group_leader_name' => $groupLeader->name,
                'total_participants' => count($users),
                'error' => $e->getMessage()
            ]);
        }

        // Log summary - payment link sent ONLY to Group Leader
        Log::info('Collective payment notification completed - ONLY Group Leader notified', [
            'group_leader_notified' => 1,
            'other_participants_skipped' => count($users) - 1,
            'group_leader_id' => $groupLeader->id,
            'total_amount' => $totalAmount,
            'invoice_url' => $invoiceUrl
        ]);
    }

    /**
     * Send collective payment link to the group leader (kept for backwards compatibility)
     */
    private function sendCollectivePaymentLink($user, $invoiceUrl, $participantDetails, $totalAmount)
    {
        try {
            // Create participant list for the message
            $participantList = "";
            foreach ($participantDetails as $index => $participant) {
                $participantList .= ($index + 1) . ". " . $participant['name'] . " - " . $participant['category'] . " (Rp " . number_format($participant['fee'], 0, ',', '.') . ")\n";
            }

            $message = "🏃‍♂️ *AMAZING SULTRA RUN - PEMBAYARAN KOLEKTIF*\n\n";
            $message .= "Halo " . $user->name . "! 👋\n\n";
            $message .= "Registrasi kolektif berhasil untuk " . count($participantDetails) . " peserta:\n\n";
            $message .= $participantList;
            $message .= "\n💰 *TOTAL PEMBAYARAN: Rp " . number_format($totalAmount, 0, ',', '.') . "*\n\n";
            $message .= "Silakan lakukan pembayaran melalui link berikut:\n";
            $message .= "🔗 " . $invoiceUrl . "\n\n";
            $message .= "ℹ️ *Petunjuk Pembayaran:*\n";
            $message .= "• Link ini berlaku untuk SEMUA peserta dalam grup Anda\n";
            $message .= "• Setelah pembayaran berhasil, SEMUA peserta akan otomatis terdaftar\n";
            $message .= "• Batas waktu pembayaran: 24 jam\n";
            $message .= "• Jersey akan dikirim ke alamat grup leader\n\n";
            $message .= "Terima kasih! 🙏\n";
            $message .= "Tim Amazing Sultra Run";

            $this->whatsappService->sendMessage($user->whatsapp_number, $message);

            Log::info('Collective payment link sent', [
                'user_id' => $user->id,
                'total_participants' => count($participantDetails),
                'total_amount' => $totalAmount,
                'invoice_url' => $invoiceUrl
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to send collective payment link', [
                'user_id' => $user->id,
                'total_participants' => count($participantDetails),
                'error' => $e->getMessage()
            ]);
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
                'city' => 'nullable|string|max:255', // Support city field
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

            // SECURITY: Check for suspicious price-related parameters
            $this->detectPriceManipulationAttempt($request);

            Log::info('API Registration validation passed', [
                'validated_data' => $validatedData,
                'ip' => $request->ip()
            ]);

            // Debug: Log all request data to check what's actually being received
            Log::info('Request field values', [
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'category' => $request->category,
                'bib_name' => $request->bib_name,
                'gender' => $request->gender,
                'birth_place' => $request->birth_place,
                'birth_date' => $request->birth_date,
                'address' => $request->address,
                'jersey_size' => $request->jersey_size,
                'whatsapp_number' => $request->whatsapp_number,
                'emergency_contact_name' => $request->emergency_contact_name,
                'emergency_contact_phone' => $request->emergency_contact_phone,
                'group_community' => $request->group_community,
                'blood_type' => $request->blood_type,
                'occupation' => $request->occupation,
                'medical_history' => $request->medical_history,
                'event_source' => $request->event_source,
            ]);

            // Format phone numbers
            $phoneNumber = $this->formatPhoneNumber($request->phone);
            $whatsappNumber = $request->whatsapp_number ? $this->formatPhoneNumber($request->whatsapp_number) : $phoneNumber;
            
            // Auto-resolve location data if city is provided but location details are missing
            $locationData = [
                'regency_id' => $request->regency_id,
                'regency_name' => $request->regency_name,
                'province_name' => $request->province_name
            ];
            
            // If location details are missing but city is provided, try to auto-resolve
            if ((!$request->regency_id || !$request->regency_name || !$request->province_name) && $request->city) {
                $resolvedLocation = $this->resolveLocationData($request->city);
                if ($resolvedLocation) {
                    $locationData = $resolvedLocation;
                    \Log::info('Location auto-resolved from city', [
                        'city' => $request->city,
                        'resolved' => $resolvedLocation
                    ]);
                }
            }

            // Log final location data after auto-resolution attempts
            \Log::info('Final location data after auto-resolution', [
                'city' => $request->city,
                'final_location_data' => $locationData,
                'has_regency_id' => !empty($locationData['regency_id']),
                'has_regency_name' => !empty($locationData['regency_name']),
                'has_province_name' => !empty($locationData['province_name'])
            ]);

            // Get and validate ticket type with quota check
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

            // Generate unique registration number
            $registrationNumber = $this->generateRegistrationNumber();

            // Prepare user data for creation
            $userData = [
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
                // Location fields (auto-resolved if possible)
                'regency_id' => $locationData['regency_id'],
                'regency_name' => $locationData['regency_name'],
                'province_name' => $locationData['province_name'],
                // Personal information from request with fallback defaults
                'gender' => $request->gender ?? 'Laki-laki',
                'birth_place' => $request->birth_place ?? 'Unknown',
                'birth_date' => $request->birth_date ?? '1990-01-01',
                'address' => $request->address ?? 'To be updated',
                'jersey_size' => $request->jersey_size ?? 'M',
                'emergency_contact_name' => $request->emergency_contact_name ?? 'To be updated',
                'emergency_contact_phone' => $request->emergency_contact_phone ? $this->formatPhoneNumber($request->emergency_contact_phone) : 'To be updated',
                'blood_type' => $request->blood_type ?? 'O',
                'occupation' => $request->occupation ?? 'Unknown',
                'group_community' => $request->group_community,
                'medical_history' => $request->medical_history,
                'event_source' => $request->event_source ?? 'Website',
                'bib_name' => $request->bib_name ?? $request->name,
            ];

            // Debug: Log the data that will be saved
            Log::info('User data to be saved', [
                'user_data' => $userData
            ]);

            // Create user with complete registration data
            try {
                $user = User::create($userData);
                
                Log::info('User created successfully in database', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'registration_number' => $user->registration_number
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to create user in database', [
                    'error' => $e->getMessage(),
                    'user_data' => $userData,
                    'trace' => $e->getTraceAsString()
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Registration failed. Please try again.',
                    'error' => 'Database error: ' . $e->getMessage()
                ], 500);
            }

            // Increment registered count for ticket type
            $ticketType->increment('registered_count');

            // Generate Xendit external_id for API registration
            $externalId = 'AMAZING-REG-' . $user->id . '-' . time();
            $user->update(['xendit_external_id' => $externalId]);

            Log::info('User created successfully', [
                'user_id' => $user->id,
                'email' => $user->email,
                'registration_number' => $user->registration_number,
                'ticket_type_id' => $ticketType->id,
                'category' => $request->category,
                'xendit_external_id' => $externalId
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

            // Create Xendit payment invoice with security validation
            try {
                // SECURITY: Validate price before creating payment
                $this->validateUserTicketPrice($user, $ticketType);
                
                $paymentResult = $this->xenditService->createInvoice(
                    $user,
                    null, // Never pass custom amount - always use database price
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
            // Generate Xendit external_id for simple API registration
            $externalId = 'AMAZING-REG-' . $user->id . '-' . time();
            $user->update(['xendit_external_id' => $externalId]);

            // Create Xendit invoice for simple API registration
            try {
                $invoice = $this->xenditService->createInvoice($user);
                if ($invoice && isset($invoice['success']) && $invoice['success'] && isset($invoice['data'])) {
                    $invoiceData = $invoice['data'];
                    $user->update([
                        'xendit_invoice_id' => $invoiceData['id'],
                        'xendit_invoice_url' => $invoiceData['invoice_url'],
                        'status' => 'registered'
                    ]);
                    
                    Log::info('Simple API invoice created successfully', [
                        'user_id' => $user->id,
                        'invoice_id' => $invoiceData['id'],
                        'invoice_url' => $invoiceData['invoice_url']
                    ]);
                } else {
                    Log::warning('Simple API invoice creation failed', [
                        'user_id' => $user->id,
                        'response' => $invoice
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('Simple API invoice creation error', [
                    'user_id' => $user->id,
                    'error' => $e->getMessage()
                ]);
            }

            $tempPassword = 'ASR' . rand(1000, 9999);
            $user->update(['password' => Hash::make($tempPassword)]);                // Try to send WhatsApp message with login credentials
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

            // Generate Xendit external_id for ultra simple API registration
            $externalId = 'AMAZING-REG-' . $user->id . '-' . time();
            $user->update(['xendit_external_id' => $externalId]);

            // Create Xendit invoice for ultra simple API registration
            try {
                $invoice = $this->xenditService->createInvoice($user);
                if ($invoice && isset($invoice['success']) && $invoice['success'] && isset($invoice['data'])) {
                    $invoiceData = $invoice['data'];
                    $user->update([
                        'xendit_invoice_id' => $invoiceData['id'],
                        'xendit_invoice_url' => $invoiceData['invoice_url'],
                        'status' => 'registered'
                    ]);
                    
                    Log::info('Ultra simple API invoice created successfully', [
                        'user_id' => $user->id,
                        'invoice_id' => $invoiceData['id'],
                        'invoice_url' => $invoiceData['invoice_url']
                    ]);
                } else {
                    Log::warning('Ultra simple API invoice creation failed', [
                        'user_id' => $user->id,
                        'response' => $invoice
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('Ultra simple API invoice creation error', [
                    'user_id' => $user->id,
                    'error' => $e->getMessage()
                ]);
            }

            Log::info('Ultra simple registration completed', [
                'user_id' => $user->id,
                'registration_number' => $user->registration_number,
                'ticket_type_id' => $ticketTypeId,
                'xendit_external_id' => $externalId
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
     * Auto-resolve location data based on city name
     */
    private function resolveLocationData($cityName)
    {
        try {
            if (!$cityName || strlen($cityName) < 2) {
                \Log::info('Location resolution skipped - city name too short', ['city' => $cityName]);
                return null;
            }
            
            \Log::info('Starting location resolution', ['city' => $cityName]);
            
            // Use direct method call instead of HTTP request to avoid routing issues
            $locationController = new \App\Http\Controllers\LocationController();
            $request = new \Illuminate\Http\Request(['q' => $cityName]);
            
            \Log::info('Calling location controller directly', ['query' => $cityName]);
            
            $response = $locationController->smartSearch($request);
            $data = json_decode($response->getContent(), true);
            
            \Log::info('Location controller response', [
                'status' => $response->getStatusCode(),
                'data' => $data,
                'raw_content' => $response->getContent(),
                'data_success' => isset($data['success']) ? $data['success'] : 'not_set',
                'data_data_empty' => empty($data['data']),
                'data_count' => isset($data['data']) ? count($data['data']) : 0
            ]);
            
            if ($response->getStatusCode() === 200 && isset($data['success']) && $data['success'] && !empty($data['data'])) {
                \Log::info('Condition check passed, processing data');
                // Return the best match (first result)
                $bestMatch = $data['data'][0];
                
                \Log::info('Best match found', ['best_match' => $bestMatch]);
                
                $result = [
                    'regency_id' => $bestMatch['regency_id'],
                    'regency_name' => $bestMatch['regency_name'],
                    'province_name' => $bestMatch['province_name']
                ];
                
                \Log::info('Location resolution successful', [
                    'city' => $cityName,
                    'result' => $result
                ]);
                
                return $result;
            } else {
                \Log::warning('Location controller failed or returned no data', [
                    'city' => $cityName,
                    'status' => $response->getStatusCode(),
                    'data' => $data
                ]);
            }
            
            return null;
            
        } catch (\Exception $e) {
            \Log::warning('Location resolution failed', [
                'city' => $cityName,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return null;
        }
    }

    /**
     * SECURITY: Validate user's ticket price against database
     * Prevents price manipulation attacks
     */
    private function validateUserTicketPrice($user, $ticketType)
    {
        try {
            // Get current ticket type price from database
            $currentTicketType = \App\Models\TicketType::getCurrentTicketType($user->race_category);
            
            if (!$currentTicketType) {
                throw new \Exception('No active ticket type found for category: ' . $user->race_category);
            }
            
            // Compare with provided ticket type
            if ($ticketType->id !== $currentTicketType->id) {
                \Log::warning('Ticket type mismatch detected', [
                    'user_id' => $user->id,
                    'provided_ticket_id' => $ticketType->id,
                    'current_ticket_id' => $currentTicketType->id,
                    'category' => $user->race_category
                ]);
                throw new \Exception('Ticket type validation failed');
            }
            
            // Validate price hasn't changed
            if ($ticketType->price !== $currentTicketType->price) {
                \Log::warning('Price change detected during registration', [
                    'user_id' => $user->id,
                    'original_price' => $ticketType->price,
                    'current_price' => $currentTicketType->price,
                    'ticket_type_id' => $ticketType->id
                ]);
                throw new \Exception('Price validation failed - price may have changed');
            }
            
            // Validate user's stored registration fee matches ticket price
            if ($user->registration_fee && $user->registration_fee != $ticketType->price) {
                \Log::warning('User registration fee mismatch', [
                    'user_id' => $user->id,
                    'stored_fee' => $user->registration_fee,
                    'ticket_price' => $ticketType->price,
                    'ticket_type_id' => $ticketType->id
                ]);
                // Update user's registration fee to match ticket price
                $user->update(['registration_fee' => $ticketType->price]);
            }
            
            \Log::info('Ticket price validation successful', [
                'user_id' => $user->id,
                'ticket_type_id' => $ticketType->id,
                'validated_price' => $ticketType->price
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Ticket price validation failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
            throw $e;
        }
    }

    /**
     * SECURITY: Detect price manipulation attempts
     */
    private function detectPriceManipulationAttempt($request)
    {
        // List of suspicious parameters that shouldn't be in registration request
        $suspiciousParams = [
            'price', 'amount', 'cost', 'fee', 'payment_amount', 
            'ticket_price', 'registration_fee', 'total', 'subtotal',
            'discount', 'coupon', 'promo_code', 'voucher'
        ];
        
        $allInput = $request->all();
        $foundSuspicious = [];
        
        foreach ($suspiciousParams as $param) {
            if (array_key_exists($param, $allInput)) {
                $foundSuspicious[$param] = $allInput[$param];
            }
        }
        
        if (!empty($foundSuspicious)) {
            \Log::warning('Price manipulation attempt detected', [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'email' => $request->email,
                'suspicious_params' => $foundSuspicious,
                'all_input' => $allInput,
                'timestamp' => now()->toDateTimeString()
            ]);
            
            // For now, just log. In production, you might want to block or challenge the request
            // throw new \Exception('Invalid request parameters detected');
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
                    } else if ($data['data'] === true && !is_array($data['data'])) {
                        // Handle case where data is just boolean true without jid/lid (10 digit number issue)
                        // Try adding 0 after 62 for 10 digit numbers
                        Log::info('WhatsApp validation returned true without jid/lid, trying with 0 prefix', [
                            'original_number' => $formattedNumber
                        ]);
                        
                        // Check if this is a 10 digit number (original 10 digits becomes 62 + 10 = 12, but for 0-prefixed becomes 62 + 9 = 11)
                        if ((strlen($formattedNumber) === 11 || strlen($formattedNumber) === 12) && str_starts_with($formattedNumber, '62')) {
                            $newFormattedNumber = '620' . substr($formattedNumber, 2);
                            
                            Log::info('Retrying WhatsApp validation with 0 prefix', [
                                'original' => $formattedNumber,
                                'new_format' => $newFormattedNumber
                            ]);
                            
                            // Retry validation with the new format
                            $retryResponse = Http::timeout(3)->get($apiUrl, [
                                'api_key' => $apiKey,
                                'sender' => $sender,
                                'number' => $newFormattedNumber
                            ]);
                            
                            if ($retryResponse->successful()) {
                                $retryData = $retryResponse->json();
                                
                                Log::info('WhatsApp API retry response received', [
                                    'phone' => $newFormattedNumber,
                                    'response' => $retryData
                                ]);
                                
                                if (isset($retryData['status']) && $retryData['status'] === true) {
                                    if (is_array($retryData['data']) && isset($retryData['data']['exists']) && $retryData['data']['exists'] === true) {
                                        return [
                                            'success' => true,
                                            'valid' => true,
                                            'message' => 'Nomor WhatsApp valid dan aktif'
                                        ];
                                    }
                                }
                            }
                        }
                        
                        // If retry failed or not applicable, treat as invalid
                        return [
                            'success' => true,
                            'valid' => false,
                            'message' => 'Nomor tidak terdaftar di WhatsApp'
                        ];
                    } else {
                        // Either data is not array structure or exists=false
                        // This handles cases like {"status":true,"data":false}
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

    /**
     * Process collective registration (multiple users at once)
     */
    public function registerKolektif(Request $request)
    {
        try {
            // ======= BULLETPROOF SECURITY VALIDATION =======
            // 1. Prevent direct price manipulation
            if ($request->has('price') || $request->has('registration_fee') || $request->has('amount')) {
                Log::critical('SECURITY ALERT: Attempted price manipulation in collective registration', [
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'request_data' => $request->all(),
                    'timestamp' => now()
                ]);
                
                return redirect()->back()
                    ->withErrors(['security' => 'Akses ditolak karena terdeteksi manipulasi data'])
                    ->withInput();
            }

            // 2. Rate limiting for collective registration (failed attempts only)
            $rateLimitKey = 'collective_registration_failed:' . $request->ip();
            $failedAttempts = Cache::get($rateLimitKey, 0);
            
            if ($failedAttempts >= 10) { // Max 10 failed attempts per hour
                Log::warning('Rate limit exceeded for collective registration', [
                    'ip' => $request->ip(),
                    'failed_attempts' => $failedAttempts
                ]);
                
                return redirect()->back()
                    ->withErrors(['rate_limit' => 'Terlalu banyak percobaan gagal. Silakan coba lagi dalam 1 jam.'])
                    ->withInput();
            }

            // 3. Verify session integrity
            if (!$request->hasValidSignature() && !$request->session()->has('_token')) {
                Log::warning('Invalid session in collective registration', [
                    'ip' => $request->ip()
                ]);
            }

            // ======= END SECURITY VALIDATION =======
            // Verify reCAPTCHA
            if ($request->has('g-recaptcha-response')) {
                $recaptchaResult = $this->recaptchaService->verify($request->input('g-recaptcha-response'));

                if (!$recaptchaResult['success']) {
                    return redirect()->back()
                        ->withErrors(['recaptcha' => 'reCAPTCHA verification failed: ' . $recaptchaResult['message']])
                        ->withInput();
                }
            }

            // Get the number of forms submitted
            $participants = $request->input('participants', []);
            
            // VALIDATION: Minimum 5 participants for collective registration
            if (empty($participants)) {
                return redirect()->back()
                    ->withErrors(['participants' => 'Registrasi kolektif minimal harus ada 5 peserta'])
                    ->withInput();
            }

            // Count valid participants (non-empty forms)
            $validParticipants = 0;
            foreach ($participants as $participant) {
                if (!empty($participant['name']) && !empty($participant['email'])) {
                    $validParticipants++;
                }
            }

            if ($validParticipants < 5) {
                return redirect()->back()
                    ->withErrors(['participants' => "Registrasi kolektif minimal harus ada 5 peserta. Saat ini hanya {$validParticipants} peserta yang lengkap."])
                    ->withInput();
            }

            Log::info('Collective registration started', [
                'total_forms' => count($participants),
                'valid_participants' => $validParticipants,
                'ip' => $request->ip()
            ]);

            $successCount = 0;
            $errors = [];
            $registrationNumbers = [];
            $successfulUsers = []; // Array to store successfully registered users

            DB::beginTransaction();

            foreach ($participants as $index => $participant) {
                try {
                    // Skip empty forms
                    if (empty($participant['name']) || empty($participant['email'])) {
                        continue;
                    }

                    // Validate individual participant data directly
                    $validationRules = [
                        'name' => 'required|string|max:255',
                        'email' => 'required|string|email|max:255',
                        'bib_name' => 'required|string|max:255',
                        'whatsapp_number' => 'required|string|max:15',
                        'gender' => 'required|in:Laki-laki,Perempuan',
                        'birth_place' => 'required|string|max:255',
                        'birth_date' => 'required|date|before:today',
                        'regency_search' => 'nullable|string|max:255',
                        'regency_id' => 'nullable|integer',
                        'regency_name' => 'nullable|string|max:255',
                        'province_name' => 'nullable|string|max:255',
                        'address' => 'required|string|max:500',
                        'jersey_size' => 'required|string|max:10',
                        'race_category' => 'required|string|max:255',
                        'emergency_contact_name' => 'required|string|max:255',
                        'emergency_contact_phone' => 'required|string|max:20',
                        'blood_type' => 'required|string|max:5',
                        'occupation' => 'required|string|max:255',
                        'event_source' => 'required|string|max:255',
                    ];

                    // Check if email already exists - DISABLED for collective registration
                    // Allow same email for collective registration
                    /*
                    $existingUser = User::where('email', $participant['email'])->first();
                    if ($existingUser) {
                        $errors["participant_" . ($index + 1)] = "Email {$participant['email']} sudah terdaftar";
                        continue;
                    }
                    */

                    // Validate participant data directly
                    $validator = Validator::make($participant, $validationRules);

                    if ($validator->fails()) {
                        $errors["participant_" . ($index + 1)] = $validator->errors()->first();
                        continue;
                    }

                    // Generate unique registration number
                    $registrationNumber = $this->generateRegistrationNumber();

                    // Format phone numbers
                    $whatsappNumber = $this->formatPhoneNumber($participant['whatsapp_number']);
                    $emergencyPhone = $this->formatPhoneNumber($participant['emergency_contact_phone'] ?? '');

                    // Get ticket type for category (use collective pricing) - SECURITY: Price from DB ONLY
                    $ticketType = $this->getCollectiveTicketTypeForCategory($participant['race_category']);
                    if (!$ticketType) {
                        $errors["participant_" . ($index + 1)] = "Kategori {$participant['race_category']} tidak tersedia";
                        continue;
                    }

                    // SECURITY: Validate official price from database - prevent bypass
                    // Use XenditService to get collective price for this category
                    $officialPrice = $this->xenditService->getCollectivePrice($participant['race_category']);
                    if ($officialPrice === false) {
                        Log::critical('SECURITY ALERT: Invalid ticket type or price manipulation attempt in collective registration', [
                            'participant_category' => $participant['race_category'],
                            'participant_index' => $index + 1,
                            'ip' => $request->ip()
                        ]);
                        
                        $errors["participant_" . ($index + 1)] = "Kategori tidak valid atau harga tidak tersedia";
                        continue;
                    }

                    // SECURITY: Double check - ensure we use database price only
                    Log::info('Price validation debug', [
                        'ticket_type_price' => $ticketType->price,
                        'official_price' => $officialPrice,
                        'ticket_type_id' => $ticketType->id,
                        'ticket_name' => $ticketType->name,
                        'participant_category' => $participant['race_category'],
                        'price_comparison' => $ticketType->price == $officialPrice ? 'MATCH' : 'MISMATCH'
                    ]);
                    
                    if ($ticketType->price != $officialPrice) {
                        Log::critical('SECURITY ALERT: Price mismatch detected in collective registration', [
                            'ticket_type_price' => $ticketType->price,
                            'official_price' => $officialPrice,
                            'participant_category' => $participant['race_category'],
                            'ticket_type_id' => $ticketType->id,
                            'ticket_name' => $ticketType->name,
                            'ip' => $request->ip()
                        ]);
                        
                        $errors["participant_" . ($index + 1)] = "Kesalahan validasi harga - Ticket: Rp" . number_format($ticketType->price, 0, ',', '.') . ", Official: Rp" . number_format($officialPrice, 0, ',', '.');
                        continue;
                    }

                    // Check quota
                    if ($ticketType->registered_count >= $ticketType->quota) {
                        $errors["participant_" . ($index + 1)] = "Kuota kategori {$participant['race_category']} sudah habis";
                        continue;
                    }

                    // Auto-resolve location data
                    $locationData = null;
                    
                    // Check if regency data is provided directly (from autocomplete)
                    if (isset($participant['regency_id']) && isset($participant['regency_name']) && isset($participant['province_name'])) {
                        $locationData = [
                            'regency_id' => $participant['regency_id'],
                            'regency_name' => $participant['regency_name'],
                            'province_name' => $participant['province_name']
                        ];
                        Log::info('Using direct regency data for participant', [
                            'index' => $index + 1,
                            'location_data' => $locationData
                        ]);
                    } elseif (isset($participant['regency_search'])) {
                        // Fallback to auto-resolution if search term provided
                        $locationData = $this->resolveLocationData($participant['regency_search']);
                        Log::info('Auto-resolved location data for participant', [
                            'index' => $index + 1,
                            'search_term' => $participant['regency_search'],
                            'location_data' => $locationData
                        ]);
                    }

                    // Create user
                    $user = User::create([
                        'name' => $participant['name'],
                        'email' => $participant['email'],
                        'phone' => $whatsappNumber,
                        'password' => bcrypt(Str::random(12)), // Random password
                        'registration_number' => $registrationNumber,
                        'bib_name' => $participant['bib_name'],
                        'gender' => $participant['gender'],
                        'birth_place' => $participant['birth_place'],
                        'birth_date' => $participant['birth_date'],
                        'address' => $participant['address'],
                        'regency_id' => $locationData['regency_id'] ?? null,
                        'regency_name' => $locationData['regency_name'] ?? null,
                        'province_name' => $locationData['province_name'] ?? null,
                        'jersey_size' => $participant['jersey_size'],
                        'race_category' => $participant['race_category'],
                        'ticket_type_id' => $ticketType->id, // Add ticket type ID
                        'registration_fee' => $officialPrice, // SECURITY: Use validated official price ONLY
                        'whatsapp_number' => $whatsappNumber,
                        'emergency_contact_name' => $participant['emergency_contact_name'],
                        'emergency_contact_phone' => $emergencyPhone,
                        'group_community' => $participant['group_community'] ?? null,
                        'blood_type' => $participant['blood_type'],
                        'occupation' => $participant['occupation'],
                        'medical_history' => $participant['medical_history'] ?? null,
                        'event_source' => $participant['event_source'],
                        'payment_status' => 'pending',
                        'status' => 'pending',
                    ]);

                    // Update ticket type registered count
                    $ticketType->increment('registered_count');

                    $successCount++;
                    $registrationNumbers[] = $registrationNumber;
                    $successfulUsers[] = $user; // Store successful user for notifications

                    Log::info('Collective registration participant created', [
                        'user_id' => $user->id,
                        'email' => $user->email,
                        'registration_number' => $registrationNumber,
                        'index' => $index + 1
                    ]);

                } catch (\Exception $e) {
                    Log::error('Error in collective registration for participant', [
                        'index' => $index + 1,
                        'participant' => $participant,
                        'error' => $e->getMessage()
                    ]);
                    $errors["participant_" . ($index + 1)] = "Gagal mendaftarkan peserta: " . $e->getMessage();
                }
            }

            if ($successCount > 0) {
                DB::commit();

                // Reset rate limit on successful registration
                $rateLimitKey = 'collective_registration_failed:' . $request->ip();
                Cache::forget($rateLimitKey);

                // Send notifications to all successfully registered users
                $this->sendCollectiveRegistrationNotifications($successfulUsers);

                $message = "Berhasil mendaftarkan {$successCount} peserta. ";
                if (!empty($errors)) {
                    $message .= "Terdapat " . count($errors) . " peserta yang gagal didaftarkan.";
                }
                $message .= " Notifikasi WhatsApp sedang dikirim ke masing-masing peserta.";

                // Redirect to success page with data
                return redirect()->route('register.kolektif.success')->with([
                    'success_message' => $message,
                    'registration_numbers' => $registrationNumbers,
                    'successful_users' => $successfulUsers,
                    'errors' => $errors,
                    'success_count' => $successCount
                ]);
            } else {
                DB::rollback();
                
                // Increment failed attempt counter
                $rateLimitKey = 'collective_registration_failed:' . $request->ip();
                $failedAttempts = Cache::get($rateLimitKey, 0);
                Cache::put($rateLimitKey, $failedAttempts + 1, 3600); // 1 hour
                
                return redirect()->back()
                    ->withErrors($errors ?: ['general' => 'Tidak ada peserta yang berhasil didaftarkan'])
                    ->withInput();
            }

        } catch (\Exception $e) {
            DB::rollback();
            
            // Increment failed attempt counter for exceptions
            $rateLimitKey = 'collective_registration_failed:' . $request->ip();
            $failedAttempts = Cache::get($rateLimitKey, 0);
            Cache::put($rateLimitKey, $failedAttempts + 1, 3600); // 1 hour
            
            Log::error('Collective registration failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->withErrors(['general' => 'Registrasi kolektif gagal: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Send WhatsApp notifications to all successfully registered users in collective registration
     */
    private function sendCollectiveRegistrationNotifications($users)
    {
        // Calculate total amount for all participants - SECURITY: From DB only
        $totalAmount = 0;
        $participantDetails = [];
        
        foreach ($users as $user) {
            // SECURITY: Re-validate price from database to prevent manipulation
            $validatedPrice = $this->xenditService->getCollectivePrice($user->race_category);
            if ($validatedPrice === false || $validatedPrice != $user->registration_fee) {
                Log::critical('SECURITY ALERT: Price manipulation detected in collective payment calculation', [
                    'user_id' => $user->id,
                    'stored_fee' => $user->registration_fee,
                    'db_price' => $validatedPrice,
                    'race_category' => $user->race_category
                ]);
                
                // Use database price as authoritative source
                if ($validatedPrice !== false) {
                    $user->update(['registration_fee' => $validatedPrice]);
                    $totalAmount += $validatedPrice;
                } else {
                    Log::error('Failed to validate price for collective payment', [
                        'user_id' => $user->id,
                        'race_category' => $user->race_category
                    ]);
                    continue; // Skip this user if price validation fails
                }
            } else {
                $totalAmount += $user->registration_fee;
            }
            
            $participantDetails[] = [
                'name' => $user->name,
                'category' => $user->race_category,
                'fee' => $user->registration_fee
            ];
        }

        // Send password and activation message ONLY to Group Leader (first participant)
        // Other participants will be managed by the Group Leader
        $groupLeader = $users[0]; // First participant is the group leader
        
        // Generate Xendit external_id for ALL users (required for database integrity)
        foreach ($users as $index => $user) {
            $externalId = 'AMAZING-REG-' . $user->id . '-' . time();
            $user->update(['xendit_external_id' => $externalId]);

            Log::info('Generated xendit_external_id for collective registration user', [
                'user_id' => $user->id,
                'email' => $user->email,
                'xendit_external_id' => $externalId,
                'is_group_leader' => $index === 0
            ]);
        }

        // Send password and activation ONLY to Group Leader
        try {
            // Generate and send random password to Group Leader only
            $passwordResult = $this->randomPasswordService->generateAndSendPassword(
                $groupLeader,
                $this->whatsappService,
                'simple'
            );

            if ($passwordResult['success']) {
                Log::info('Password sent ONLY to Group Leader for collective registration', [
                    'group_leader_id' => $groupLeader->id,
                    'group_leader_email' => $groupLeader->email,
                    'password_sent' => $passwordResult['password_sent'],
                    'total_participants_managed' => count($users)
                ]);

                // Send activation message to Group Leader only
                $activationResult = $this->sendActivationMessage($groupLeader);

                if ($activationResult['success']) {
                    // Automatically verify WhatsApp ONLY for Group Leader
                    $groupLeader->update([
                        'whatsapp_verified' => true,
                        'whatsapp_verified_at' => now(),
                        'status' => 'verified'
                    ]);

                    // Mark other participants as pending (no WhatsApp verification)
                    for ($i = 1; $i < count($users); $i++) {
                        $users[$i]->update([
                            'whatsapp_verified' => false,
                            'whatsapp_verified_at' => null,
                            'status' => 'pending' // Will be updated after payment
                        ]);
                        
                        Log::info('Non-leader participant status set to pending (no notifications sent)', [
                            'user_id' => $users[$i]->id,
                            'user_name' => $users[$i]->name,
                            'participant_index' => $i + 1,
                            'managed_by_leader' => $groupLeader->id,
                            'notifications_policy' => 'LEADER_ONLY'
                        ]);
                    }

                    Log::info('Group Leader activated via WhatsApp - manages all participants', [
                        'group_leader_id' => $groupLeader->id,
                        'group_leader_email' => $groupLeader->email,
                        'group_leader_whatsapp' => $groupLeader->whatsapp_number,
                        'total_participants_managed' => count($users)
                    ]);
                }
            } else {
                Log::error('Failed to send password to Group Leader for collective registration', [
                    'group_leader_id' => $groupLeader->id,
                    'group_leader_email' => $groupLeader->email,
                    'error' => $passwordResult['message']
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Exception in collective registration Group Leader notification', [
                'group_leader_id' => $groupLeader->id,
                'group_leader_email' => $groupLeader->email,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }

        // Create ONE collective payment invoice for all participants
        if (!empty($users)) {
            try {
                $firstUser = $users[0]; // Use first user as the primary payer
                $collectiveDescription = "Amazing Sultra Run - Registrasi Kolektif (" . count($users) . " peserta)";
                
                // SECURITY: Validate total amount before creating invoice
                if ($totalAmount <= 0) {
                    Log::critical('SECURITY ALERT: Invalid total amount for collective payment', [
                        'total_amount' => $totalAmount,
                        'participant_count' => count($users),
                        'first_user_id' => $firstUser->id
                    ]);
                    throw new \Exception('Invalid payment amount calculated');
                }

                // SECURITY: Double-check amount calculation
                $recalculatedTotal = 0;
                foreach ($users as $checkUser) {
                    $checkPrice = $this->xenditService->getCollectivePrice($checkUser->race_category);
                    if ($checkPrice === false) {
                        throw new \Exception('Price validation failed for user ID: ' . $checkUser->id . ' with category: ' . $checkUser->race_category);
                    }
                    $recalculatedTotal += $checkPrice;
                }

                if ($recalculatedTotal !== $totalAmount) {
                    Log::critical('SECURITY ALERT: Amount mismatch in collective payment', [
                        'calculated_total' => $totalAmount,
                        'recalculated_total' => $recalculatedTotal,
                        'difference' => abs($totalAmount - $recalculatedTotal)
                    ]);
                    throw new \Exception('Payment amount validation failed');
                }
                
                // Create collective payment invoice with security validation
                $paymentResult = $this->xenditService->createCollectiveInvoice(
                    $firstUser,
                    $participantDetails,
                    $totalAmount,
                    $collectiveDescription
                );

                if ($paymentResult['success']) {
                    // Update all users with the same invoice ID for tracking
                    foreach ($users as $user) {
                        $user->update([
                            'xendit_invoice_id' => $paymentResult['invoice_id'] ?? null,
                            'xendit_invoice_url' => $paymentResult['invoice_url'] ?? null,
                            'payment_description' => $collectiveDescription,
                            'status' => 'registered' // Update status to registered
                        ]);
                        
                        Log::info('Updated collective registration user with payment data', [
                            'user_id' => $user->id,
                            'xendit_external_id' => $user->xendit_external_id,
                            'xendit_invoice_id' => $paymentResult['invoice_id'] ?? null
                        ]);
                    }

                    // Send collective payment link ONLY to Group Leader (first participant)
                    $this->sendCollectivePaymentLinkToLeader($users, $paymentResult['invoice_url'], $participantDetails, $totalAmount);

                    Log::info('Collective payment invoice created', [
                        'primary_user_id' => $firstUser->id,
                        'total_participants' => count($users),
                        'total_amount' => $totalAmount,
                        'invoice_url' => $paymentResult['invoice_url']
                    ]);
                } else {
                    Log::error('Failed to create collective payment invoice', [
                        'primary_user_id' => $firstUser->id,
                        'total_participants' => count($users),
                        'total_amount' => $totalAmount,
                        'error' => $paymentResult['message']
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('Exception in collective payment creation', [
                    'total_participants' => count($users),
                    'total_amount' => $totalAmount,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        }
    }

    /**
     * Get ticket type for a given race category
     */
    private function getTicketTypeForCategory($categoryName)
    {
        try {
            // First try using the getCurrentTicketType method
            $ticketType = \App\Models\TicketType::getCurrentTicketType($categoryName);
            
            // If not found, try manual search with race category relationship
            if (!$ticketType) {
                $ticketType = \App\Models\TicketType::whereHas('raceCategory', function($query) use ($categoryName) {
                    $query->where('name', $categoryName);
                })->where('is_active', true)->first();
            }
            
            return $ticketType;
        } catch (\Exception $e) {
            Log::error('Error getting ticket type for category', [
                'category' => $categoryName,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Get collective ticket type (special price) for a given race category
     */
    private function getCollectiveTicketTypeForCategory($categoryName)
    {
        try {
            // Look for collective ticket types (with "Kolektif" in name)
            $ticketType = \App\Models\TicketType::whereHas('raceCategory', function($query) use ($categoryName) {
                $query->where('name', $categoryName);
            })
            ->where('is_active', true)
            ->where(function($query) {
                $query->where('name', 'LIKE', '%kolektif%')
                      ->orWhere('name', 'LIKE', '%Kolektif%')
                      ->orWhere('name', 'LIKE', '%collective%')
                      ->orWhere('name', 'LIKE', '%Collective%');
            })
            ->first();
            
            if (!$ticketType) {
                Log::warning('No collective ticket type found for category, using regular price', [
                    'category' => $categoryName
                ]);
                // Fallback to regular ticket type
                return $this->getTicketTypeForCategory($categoryName);
            }
            
            return $ticketType;
        } catch (\Exception $e) {
            Log::error('Error getting collective ticket type for category', [
                'category' => $categoryName,
                'error' => $e->getMessage()
            ]);
            // Fallback to regular ticket type
            return $this->getTicketTypeForCategory($categoryName);
        }
    }

    /**
     * Display collective registration success page
     */
    public function collectiveSuccess()
    {
        // Check if we have success data in session
        if (!session()->has('success_message')) {
            // Create sample data for testing in both local and production
            session([
                'success_message' => 'Berhasil mendaftarkan peserta. Notifikasi WhatsApp sedang dikirim ke masing-masing peserta.',
                'registration_numbers' => ['ASR202500001', 'ASR202500002', 'ASR202500003'],
                'success_count' => 3,
                'successful_users' => [
                    (object)[
                        'name' => 'Peserta 1',
                        'email' => 'peserta1@example.com',
                        'bib_name' => 'RUNNER1',
                        'race_category_name' => '5K',
                        'registration_fee' => 150000
                    ],
                    (object)[
                        'name' => 'Peserta 2',
                        'email' => 'peserta2@example.com',
                        'bib_name' => 'RUNNER2',
                        'race_category_name' => '10K',
                        'registration_fee' => 200000
                    ],
                    (object)[
                        'name' => 'Peserta 3',
                        'email' => 'peserta3@example.com',
                        'bib_name' => 'RUNNER3',
                        'race_category_name' => '21K',
                        'registration_fee' => 250000
                    ]
                ],
                'errors' => []
            ]);
        }

        // Ensure successful_users is always an array and properly formatted
        $successfulUsers = session('successful_users', []);
        
        // Convert to consistent object format if needed
        if (!empty($successfulUsers)) {
            $formattedUsers = [];
            foreach ($successfulUsers as $user) {
                // Handle both array and object formats
                if (is_array($user)) {
                    $formattedUsers[] = (object) $user;
                } elseif (is_object($user)) {
                    // Ensure required fields exist
                    $userData = (object) [
                        'name' => $user->name ?? 'Unknown',
                        'email' => $user->email ?? 'unknown@example.com',
                        'bib_name' => $user->bib_name ?? 'BIB000',
                        'race_category_name' => $user->race_category_name ?? $user->race_category ?? '5K',
                        'registration_fee' => $user->registration_fee ?? 0
                    ];
                    $formattedUsers[] = $userData;
                }
            }
            
            // Update session with formatted data
            session(['successful_users' => $formattedUsers]);
        }

        return view('auth.collective-success');
    }

    /**
     * Process wakaf registration (same as regular registration but restricted to wakaf ticket type)
     */
    public function registerWakaf(Request $request)
    {
        // Log request received
        Log::info('Wakaf registration request received', [
            'email' => $request->email,
            'name' => $request->name,
            'whatsapp_number' => $request->whatsapp_number,
            'has_recaptcha' => $request->has('g-recaptcha-response')
        ]);

        // Verify reCAPTCHA
        if ($request->has('g-recaptcha-response')) {
            $recaptchaResult = $this->recaptchaService->verify($request->input('g-recaptcha-response'));

            if (!$recaptchaResult['success']) {
                Log::warning('Wakaf registration reCAPTCHA failed', ['result' => $recaptchaResult]);
                return redirect()->back()
                    ->withErrors(['recaptcha' => 'reCAPTCHA verification failed: ' . $recaptchaResult['message']])
                    ->withInput();
            }
            Log::info('Wakaf registration reCAPTCHA verified successfully');
        }

        $validationRules = [
            'name' => 'required|string|max:255',
            'bib_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'whatsapp_number' => 'required|string|max:15|unique:users,whatsapp_number',
            'phone' => 'nullable|string|max:15',
            'birth_place' => 'required|string|max:255',
            'birth_date' => 'required|date|before:today',
            'gender' => 'required|in:Laki-laki,Perempuan',
            'group_community' => 'nullable|string|max:255',
            'blood_type' => 'required|string',
            'occupation' => 'required|string|max:255',
            'medical_history' => 'nullable|string|max:1000',
            'emergency_contact_name' => 'required|string|max:255',
            'emergency_contact_phone' => 'required|string|max:20|regex:/^[0-9+\-\s]+$/',
            'address' => 'required|string|max:1000',
            'regency_id' => 'required|string|max:255',
            'regency_name' => 'required|string|max:255',
            'province_name' => 'required|string|max:255',
            'jersey_size_id' => 'required|exists:jersey_sizes,id',
            'event_source' => 'required|string',
        ];

        Log::info('Wakaf registration starting validation');
        
        try {
            $request->validate($validationRules);
            Log::info('Wakaf registration validation passed');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Wakaf registration validation failed', [
                'errors' => $e->errors(),
                'input' => $request->except(['password', 'g-recaptcha-response'])
            ]);
            throw $e;
        }

        // Validate regency_id from Redis cache
        if (!$this->validateRegencyFromRedis($request->regency_id)) {
            return redirect()->back()
                ->withErrors(['regency_id' => 'Kota/Kabupaten yang dipilih tidak valid.'])
                ->withInput();
        }

        // Get wakaf ticket type
        $wakafTicketType = TicketType::where('name', 'wakaf')
            ->where('is_active', true)
            ->with('raceCategory')
            ->first();

        if (!$wakafTicketType) {
            return redirect()->back()
                ->withErrors(['wakaf' => 'Pendaftaran Wakaf saat ini tidak tersedia.'])
                ->withInput();
        }

        try {
            // Log request data for debugging
            Log::info('Wakaf registration request data', [
                'jersey_size_id' => $request->jersey_size_id,
                'name' => $request->name,
                'email' => $request->email,
                'has_jersey_size_id' => !empty($request->jersey_size_id)
            ]);
            
            // Format phone numbers
            $whatsappNumber = $this->formatPhoneNumber($request->whatsapp_number);
            $phone = $request->phone ? $this->formatPhoneNumber($request->phone) : null;
            $emergencyPhone = $this->formatPhoneNumber($request->emergency_contact_phone);

            // Generate unique registration number
            $registrationNumber = $this->generateRegistrationNumber();
            
            // Get jersey size name for consistency
            $jerseySize = null;
            if ($request->jersey_size_id) {
                $jerseyRecord = \App\Models\JerseySize::find($request->jersey_size_id);
                $jerseySize = $jerseyRecord ? $jerseyRecord->name : null; // Use 'name' field, not 'size'
                
                Log::info('Jersey size retrieved for wakaf user', [
                    'jersey_size_id' => $request->jersey_size_id,
                    'jersey_size' => $jerseySize,
                    'jersey_record_found' => !is_null($jerseyRecord)
                ]);
            }
            
            // Get race category name for consistency
            $raceCategoryName = $wakafTicketType->raceCategory ? $wakafTicketType->raceCategory->name : 'wakaf';

            // Create user with temporary password
            $temporaryPassword = 'temp_' . time();
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($temporaryPassword),
                'whatsapp_number' => $whatsappNumber,
                'phone' => $phone,
                'role' => 'user',
                'status' => 'pending',
                'registration_number' => $registrationNumber,
                'is_active' => true,
                'payment_status' => 'pending',
                'ticket_type_id' => $wakafTicketType->id, // Add ticket type ID
                'bib_name' => $request->bib_name,
                'birth_place' => $request->birth_place,
                'birth_date' => $request->birth_date,
                'gender' => $request->gender,
                'group_community' => $request->group_community,
                'blood_type' => $request->blood_type,
                'occupation' => $request->occupation,
                'medical_history' => $request->medical_history,
                'emergency_contact_name' => $request->emergency_contact_name,
                'emergency_contact_phone' => $emergencyPhone,
                'address' => $request->address,
                'regency_id' => $request->regency_id,
                'regency_name' => $request->regency_name,
                'province_name' => $request->province_name,
                'jersey_size_id' => $request->jersey_size_id,
                'jersey_size' => $jerseySize, // Add jersey size string
                'race_category' => $raceCategoryName, // Add race category string
                'race_category_id' => $wakafTicketType->race_category_id,
                'event_source' => $request->event_source,
                'registration_date' => now(),
                'is_verified' => false,
                'is_collective' => false,
            ]);

            Log::info('Wakaf user created successfully', ['user_id' => $user->id, 'email' => $user->email]);

            // Update registered count
            $wakafTicketType->increment('registered_count');

            // Generate Xendit external_id for wakaf registration
            $externalId = 'AMAZING-WAKAF-' . $user->id . '-' . time();
            $user->update(['xendit_external_id' => $externalId]);

            Log::info('Generated xendit_external_id for wakaf registration', [
                'user_id' => $user->id,
                'email' => $user->email,
                'xendit_external_id' => $externalId
            ]);

            // Generate and send random password (same as regular registration)
            $passwordResult = $this->randomPasswordService->generateAndSendPassword(
                $user,
                $this->whatsappService,
                'simple'
            );
            
            if (!$passwordResult['success']) {
                // Log warning but continue with registration
                Log::warning('Failed to send automatic password via WhatsApp for wakaf user', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'error' => $passwordResult['message'] ?? 'Unknown error'
                ]);
            } else {
                Log::info('Password sent successfully to wakaf user', [
                    'user_id' => $user->id,
                    'email' => $user->email
                ]);
            }

            // Send WhatsApp activation message and verify account automatically (same as regular registration)
            $activationResult = $this->sendActivationMessage($user);

            if ($activationResult['success']) {
                // Automatically verify WhatsApp if message sent successfully
                $user->update([
                    'whatsapp_verified' => true,
                    'whatsapp_verified_at' => now(),
                    'status' => 'verified' // Change status to verified
                ]);

                Log::info('Wakaf user registered and WhatsApp verified automatically', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'whatsapp' => $user->whatsapp_number
                ]);
            } else {
                Log::warning('Wakaf user registered but WhatsApp activation failed', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'whatsapp' => $user->whatsapp_number,
                    'error' => $activationResult['message']
                ]);
            }

            // Create invoice using XenditService (same as regular registration)
            $invoiceData = $this->xenditService->createInvoice(
                $user,
                null, // Let the service use the user's race category price
                'Amazing Sultra Run Wakaf Registration Fee - ' . $user->name
            );

            if ($invoiceData['success']) {
                // Update user with xendit_url for completeness
                $user->update(['xendit_url' => $invoiceData['invoice_url']]);
                
                // Send payment link via WhatsApp (same as regular registration)
                $this->sendPaymentLink($user, $invoiceData['invoice_url']);

                // Login user
                Auth::login($user);

                Log::info('Wakaf registration completed successfully', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'registration_number' => $user->registration_number,
                    'ticket_type_id' => $user->ticket_type_id,
                    'jersey_size' => $user->jersey_size,
                    'race_category' => $user->race_category,
                    'xendit_external_id' => $user->xendit_external_id,
                    'xendit_url' => $user->xendit_url,
                    'invoice_id' => $invoiceData['invoice_id'],
                    'invoice_url' => $invoiceData['invoice_url']
                ]);

                // Store success data in session for wakaf success page
                session([
                    'wakaf_success_data' => [
                        'user_name' => $user->name,
                        'email' => $user->email,
                        'registration_number' => $user->registration_number,
                        'race_category' => $user->race_category,
                        'jersey_size' => $user->jersey_size,
                        'whatsapp_number' => $user->whatsapp_number,
                        'invoice_url' => $invoiceData['invoice_url'],
                        'amount' => $wakafTicketType->price,
                        'message' => 'Registrasi Wakaf berhasil! Link pembayaran telah dikirim ke WhatsApp Anda. Silakan lakukan pembayaran untuk menyelesaikan pendaftaran.'
                    ]
                ]);

                return redirect()->route('wakaf.success');
            } else {
                Log::error('Wakaf registration failed to create invoice', [
                    'user_id' => $user->id,
                    'error' => $invoiceData['message']
                ]);
                return redirect()->back()
                    ->withErrors(['payment' => 'Gagal membuat invoice pembayaran: ' . $invoiceData['message']])
                    ->withInput();
            }

        } catch (\Exception $e) {
            \Log::error('Wakaf registration error: ' . $e->getMessage());
            return redirect()->back()
                ->withErrors(['registration' => 'Registrasi gagal. Silakan coba lagi.'])
                ->withInput();
        }
    }

    /**
     * Display wakaf registration success page
     */
    public function wakafSuccess()
    {
        // Check if we have success data in session
        if (!session()->has('wakaf_success_data')) {
            // Redirect to login if no success data
            return redirect()->route('login')
                ->with('error', 'Session wakaf tidak ditemukan. Silakan coba lagi.');
        }

        $successData = session('wakaf_success_data');
        
        // Clear the session data after retrieving it
        session()->forget('wakaf_success_data');

        return view('auth.wakaf-success', compact('successData'));
    }

    /**
     * Validate regency ID from Redis cache
     */
    private function validateRegencyFromRedis($regencyId)
    {
        try {
            $redis = new \Predis\Client([
                'host' => '127.0.0.1',
                'port' => 60977,
                'password' => 'GSozWrvKtn18hzjCZ6j',
                'timeout' => 5
            ]);

            $allRegenciesJson = $redis->get('all_regencies');
            
            if (!$allRegenciesJson) {
                // If Redis cache is empty, allow any regency ID (fallback)
                \Log::warning('Redis regencies cache is empty, allowing regency ID: ' . $regencyId);
                return true;
            }

            $allRegencies = json_decode($allRegenciesJson, true);
            
            if (!is_array($allRegencies)) {
                \Log::warning('Invalid regencies data in Redis');
                return true; // Fallback to allow
            }

            // Check if regency ID exists in cache
            foreach ($allRegencies as $regency) {
                if (isset($regency['id']) && $regency['id'] == $regencyId) {
                    return true;
                }
            }

            \Log::info('Regency ID not found in Redis cache', ['regency_id' => $regencyId]);
            return false;

        } catch (\Exception $e) {
            \Log::error('Error validating regency from Redis', [
                'regency_id' => $regencyId,
                'error' => $e->getMessage()
            ]);
            // On error, allow the regency (fallback behavior)
            return true;
        }
    }
}
