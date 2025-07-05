<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class PasswordResetController extends Controller
{
    public function showResetForm()
    {
        return view('auth.password-reset');
    }

    public function checkUsername(Request $request)
    {
        // Debug logging
        \Log::info('checkUsername method called', ['request' => $request->all()]);
        
        $validator = Validator::make($request->all(), [
            'whatsapp_number' => 'required|string|min:10|max:15',
        ]);

        if ($validator->fails()) {
            \Log::info('Validation failed', ['errors' => $validator->errors()]);
            return response()->json([
                'success' => false,
                'message' => 'Nomor WhatsApp tidak valid.',
                'errors' => $validator->errors()
            ]);
        }

        // Format WhatsApp number
        $whatsappNumber = $this->formatWhatsAppNumber($request->whatsapp_number);
        \Log::info('Formatted WhatsApp number', ['original' => $request->whatsapp_number, 'formatted' => $whatsappNumber]);

        // Find user by WhatsApp number
        $user = User::where('whatsapp_number', $whatsappNumber)->first();
        \Log::info('User search result', ['found' => !!$user, 'user_id' => $user ? $user->id : null]);

        if ($user) {
            return response()->json([
                'success' => true,
                'username' => $user->email, // Email sebagai username
                'name' => $user->name
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Nomor WhatsApp tidak ditemukan dalam sistem.'
            ]);
        }
    }

    public function checkUsernameSimple(Request $request)
    {
        try {
            $whatsappNumber = $request->input('whatsapp_number');
            
            if (empty($whatsappNumber)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nomor WhatsApp tidak boleh kosong'
                ]);
            }

            // Format WhatsApp number
            $formattedNumber = $whatsappNumber;
            if (!str_starts_with($formattedNumber, '62')) {
                if (str_starts_with($formattedNumber, '0')) {
                    $formattedNumber = '62' . substr($formattedNumber, 1);
                } elseif (str_starts_with($formattedNumber, '8')) {
                    $formattedNumber = '62' . $formattedNumber;
                }
            }

            // Find user
            $user = User::where('whatsapp_number', $formattedNumber)->first();

            if ($user) {
                return response()->json([
                    'success' => true,
                    'username' => $user->email,
                    'name' => $user->name,
                    'formatted_number' => $formattedNumber
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Nomor WhatsApp tidak ditemukan dalam sistem',
                    'formatted_number' => $formattedNumber
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    public function sendResetLink(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'whatsapp_number' => 'required|string|min:10|max:15',
        ], [
            'whatsapp_number.required' => 'Nomor WhatsApp wajib diisi.',
            'whatsapp_number.min' => 'Nomor WhatsApp minimal 10 digit.',
            'whatsapp_number.max' => 'Nomor WhatsApp maksimal 15 digit.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Format WhatsApp number
        $whatsappNumber = $this->formatWhatsAppNumber($request->whatsapp_number);

        // Find user by WhatsApp number
        $user = User::where('whatsapp_number', $whatsappNumber)->first();

        if (!$user) {
            return redirect()->back()
                ->with('error', 'Nomor WhatsApp tidak ditemukan dalam sistem.')
                ->withInput();
        }

        try {
            // Generate reset token
            $token = Str::random(60);
            
            // Store token in database (you might want to create a separate table for this)
            DB::table('password_resets')->updateOrInsert(
                ['whatsapp_number' => $whatsappNumber],
                [
                    'token' => Hash::make($token),
                    'created_at' => now()
                ]
            );

            // Create reset URL
            $resetUrl = route('password.reset.form', ['token' => $token, 'whatsapp' => $whatsappNumber]);

            // Send WhatsApp message
            $messageSent = $this->sendWhatsAppMessage($whatsappNumber, $user->name, $resetUrl);

            if ($messageSent) {
                return redirect()->back()
                    ->with('success', 'Link reset password telah dikirim ke WhatsApp Anda. Silakan cek pesan masuk.');
            } else {
                return redirect()->back()
                    ->with('error', 'Gagal mengirim pesan WhatsApp. Silakan coba lagi.');
            }

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan sistem. Silakan coba lagi.')
                ->withInput();
        }
    }

    public function showResetPasswordForm(Request $request, $token)
    {
        $whatsappNumber = $request->query('whatsapp');
        
        if (!$whatsappNumber || !$token) {
            return redirect()->route('password.reset')
                ->with('error', 'Link reset password tidak valid.');
        }

        // Verify token
        $resetRecord = DB::table('password_resets')
            ->where('whatsapp_number', $whatsappNumber)
            ->first();

        if (!$resetRecord || !Hash::check($token, $resetRecord->token)) {
            return redirect()->route('password.reset')
                ->with('error', 'Link reset password tidak valid atau sudah expired.');
        }

        // Check if token is not older than 1 hour
        if (now()->diffInMinutes($resetRecord->created_at) > 60) {
            DB::table('password_resets')->where('whatsapp_number', $whatsappNumber)->delete();
            return redirect()->route('password.reset')
                ->with('error', 'Link reset password sudah expired. Silakan request ulang.');
        }

        return view('auth.password-reset-form', compact('token', 'whatsappNumber'));
    }

    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'whatsapp_number' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Verify token again
        $resetRecord = DB::table('password_resets')
            ->where('whatsapp_number', $request->whatsapp_number)
            ->first();

        if (!$resetRecord || !Hash::check($request->token, $resetRecord->token)) {
            return redirect()->route('password.reset')
                ->with('error', 'Token reset password tidak valid.');
        }

        // Find user
        $user = User::where('whatsapp_number', $request->whatsapp_number)->first();

        if (!$user) {
            return redirect()->route('password.reset')
                ->with('error', 'User tidak ditemukan.');
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        // Delete reset token
        DB::table('password_resets')->where('whatsapp_number', $request->whatsapp_number)->delete();

        // Send confirmation message
        $this->sendConfirmationMessage($request->whatsapp_number, $user->name);

        return redirect()->route('login')
            ->with('success', 'Password berhasil direset. Silakan login dengan password baru Anda.');
    }

    private function formatWhatsAppNumber($number)
    {
        // Remove all non-numeric characters
        $number = preg_replace('/[^0-9]/', '', $number);
        
        // Add country code if not present
        if (strlen($number) > 0) {
            if (substr($number, 0, 2) !== '62') {
                if (substr($number, 0, 1) === '0') {
                    $number = '62' . substr($number, 1);
                } else if (substr($number, 0, 1) === '8') {
                    $number = '62' . $number;
                }
            }
        }
        
        return $number;
    }

    private function sendWhatsAppMessage($whatsappNumber, $userName, $resetUrl)
    {
        try {
            $apiKey = config('app.whatsapp_reset_api_key');
            $senderNumber = config('app.whatsapp_reset_sender');
            $apiUrl = config('app.whatsapp_reset_api_url');

            $message = "Halo {$userName}!\n\n";
            $message .= "Anda telah meminta reset password untuk akun event amazingsultrarun.\n\n";
            $message .= "Klik link berikut untuk reset password Anda:\n";
            $message .= "{$resetUrl}\n\n";
            $message .= "Link ini berlaku selama 1 jam.\n\n";
            $message .= "Jika Anda tidak meminta reset password, abaikan pesan ini.\n\n";
            $message .= "Terima kasih!";

            $response = Http::timeout(30)->get($apiUrl, [
                'api_key' => $apiKey,
                'sender' => $senderNumber,
                'number' => $whatsappNumber,
                'message' => $message
            ]);

            if ($response->successful()) {
                $data = $response->json();
                // Log the response for debugging
                \Log::info('WhatsApp API Response for reset password', $data);
                return true;
            }

            \Log::error('WhatsApp API Error for reset password', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            return false;

        } catch (\Exception $e) {
            \Log::error('WhatsApp API Exception for reset password', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }

    private function sendConfirmationMessage($whatsappNumber, $userName)
    {
        try {
            $apiKey = config('app.whatsapp_reset_api_key');
            $senderNumber = config('app.whatsapp_reset_sender');
            $apiUrl = config('app.whatsapp_reset_api_url');

            $message = "Halo {$userName}!\n\n";
            $message .= "Password Anda telah berhasil direset.\n\n";
            $message .= "Jika ini bukan Anda yang melakukan reset, segera hubungi admin.\n\n";
            $message .= "Terima kasih!";

            Http::timeout(30)->get($apiUrl, [
                'api_key' => $apiKey,
                'sender' => $senderNumber,
                'number' => $whatsappNumber,
                'message' => $message
            ]);

        } catch (\Exception $e) {
            \Log::error('WhatsApp confirmation message failed', [
                'message' => $e->getMessage()
            ]);
        }
    }
}
