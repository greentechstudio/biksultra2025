<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\JerseySize;
use App\Models\RaceCategory;
use App\Models\BloodType;
use App\Models\EventSource;
use App\Services\XenditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    private $xenditService;

    public function __construct(XenditService $xenditService)
    {
        $this->xenditService = $xenditService;
    }
    public function showRegister()
    {
        $jerseySizes = JerseySize::active()->get();
        $raceCategories = RaceCategory::active()->get();
        $bloodTypes = BloodType::active()->get();
        $eventSources = EventSource::active()->get();

        return view('auth.register', compact('jerseySizes', 'raceCategories', 'bloodTypes', 'eventSources'));
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'bib_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:15',
            'password' => 'required|string|min:8|confirmed',
            'gender' => 'required|in:Laki-laki,Perempuan',
            'birth_place' => 'required|string|max:255',
            'birth_date' => 'required|date|before:today',
            'address' => 'required|string|max:500',
            'jersey_size' => 'required|string|max:10',
            'race_category' => 'required|string|max:255',
            'whatsapp_number' => 'required|string|max:15',
            'emergency_contact_1' => 'required|string|max:255',
            'emergency_contact_2' => 'nullable|string|max:255',
            'group_community' => 'nullable|string|max:255',
            'blood_type' => 'required|string|max:5',
            'occupation' => 'required|string|max:255',
            'medical_history' => 'nullable|string|max:1000',
            'event_source' => 'required|string|max:255',
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
                $isValidWhatsApp = $this->validateWhatsAppNumber($whatsappNumber);
                if (!$isValidWhatsApp) {
                    return redirect()->back()
                        ->withErrors(['whatsapp_number' => 'Nomor WhatsApp tidak valid atau tidak terdaftar'])
                        ->withInput();
                }
            } catch (\Exception $e) {
                // Log the error but don't block registration
                Log::warning('WhatsApp validation failed during registration', [
                    'number' => $whatsappNumber,
                    'error' => $e->getMessage()
                ]);
            }
        }

        $user = User::create([
            'name' => $request->name,
            'bib_name' => $request->bib_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => 'user', // Default role for new registrations
            'status' => 'pending',
            'gender' => $request->gender,
            'birth_place' => $request->birth_place,
            'birth_date' => $request->birth_date,
            'address' => $request->address,
            'jersey_size' => $request->jersey_size,
            'race_category' => $request->race_category,
            'whatsapp_number' => $whatsappNumber, // Use formatted number
            'emergency_contact_1' => $request->emergency_contact_1,
            'emergency_contact_2' => $request->emergency_contact_2,
            'group_community' => $request->group_community,
            'blood_type' => $request->blood_type,
            'occupation' => $request->occupation,
            'medical_history' => $request->medical_history,
            'event_source' => $request->event_source,
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
            $message .= "• Password: (yang Anda buat saat registrasi)\n\n";
            $message .= "🌐 Link Login: " . url('/login') . "\n\n";
            $message .= "📱 Untuk informasi lebih lanjut atau bantuan, silakan hubungi admin.\n\n";
            $message .= "Terima kasih dan selamat berlari! 🏃‍♂️🏃‍♀️\n\n";
            $message .= "_Tim Amazing Sultra Run_";

            // Send message via internal API
            $response = Http::timeout(30)->post(url('/api/whatsapp/send-message'), [
                'number' => $user->whatsapp_number,
                'message' => $message
            ]);

            if ($response->successful()) {
                $data = $response->json();
                if ($data['success']) {
                    return [
                        'success' => true,
                        'message' => 'Pesan aktivasi berhasil dikirim',
                        'data' => $data
                    ];
                } else {
                    return [
                        'success' => false,
                        'message' => $data['message'] ?? 'Gagal mengirim pesan aktivasi'
                    ];
                }
            } else {
                return [
                    'success' => false,
                    'message' => 'Gagal terhubung ke service WhatsApp'
                ];
            }
        } catch (\Exception $e) {
            Log::error('Failed to send activation message', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
            
            return [
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengirim pesan aktivasi: ' . $e->getMessage()
            ];
        }
    }

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
    
    private function validateWhatsAppNumber($phoneNumber)
    {
        try {
            $apiKey = env('WHATSAPP_API_KEY', 'tZiKYy1sHXasOj0hDGZnRfAnAYo2Ec');
            $sender = env('WHATSAPP_SENDER', '628114040707');
            $apiUrl = 'https://wamd.system112.org/check-number';
            
            $response = Http::timeout(10)->get($apiUrl, [
                'api_key' => $apiKey,
                'sender' => $sender,
                'number' => $phoneNumber
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                // Handle API response format: {"status": true, "data": {"jid": "...", "exists": true}}
                if (isset($data['status']) && $data['status'] === true) {
                    if (isset($data['data']['exists']) && $data['data']['exists'] === true) {
                        return true;
                    }
                }
                
                return false;
            }
            
            return false; // API call failed
        } catch (\Exception $e) {
            throw $e;
        }
    }
    
    /**
     * Send payment link via WhatsApp
     */
    private function sendPaymentLink($user, $invoiceUrl)
    {
        try {
            $apiKey = env('WHATSAPP_API_KEY', 'tZiKYy1sHXasOj0hDGZnRfAnAYo2Ec');
            $sender = env('WHATSAPP_SENDER', '628114040707');
            $apiUrl = 'https://wamd.system112.org/send-message';
            
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

            $response = Http::timeout(30)->post($apiUrl, [
                'api_key' => $apiKey,
                'sender' => $sender,
                'number' => $user->whatsapp_number,
                'message' => $message
            ]);

            if ($response->successful() && $response->json('status') === true) {
                Log::info('Payment link sent successfully via WhatsApp', [
                    'user_id' => $user->id,
                    'whatsapp_number' => $user->whatsapp_number,
                    'invoice_url' => $invoiceUrl
                ]);
                return true;
            } else {
                Log::error('Failed to send payment link via WhatsApp', [
                    'user_id' => $user->id,
                    'whatsapp_number' => $user->whatsapp_number,
                    'response' => $response->json()
                ]);
                return false;
            }
        } catch (\Exception $e) {
            Log::error('Exception sending payment link via WhatsApp', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
}
