<?php

namespace App\Services;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class RandomPasswordService
{
    /**
     * Generate a random password
     */
    public function generateRandomPassword($length = 8)
    {
        // Generate password with mix of letters, numbers, and symbols
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*';
        $password = '';
        
        for ($i = 0; $i < $length; $i++) {
            $password .= $characters[random_int(0, strlen($characters) - 1)];
        }
        
        return $password;
    }
    
    /**
     * Generate a simple numeric password (easier to type)
     */
    public function generateSimplePassword($length = 6)
    {
        $numbers = '0123456789';
        $letters = 'abcdefghijklmnopqrstuvwxyz';
        $password = '';
        
        // Start with 2 letters
        for ($i = 0; $i < 2; $i++) {
            $password .= $letters[random_int(0, strlen($letters) - 1)];
        }
        
        // Add 4 numbers
        for ($i = 0; $i < ($length - 2); $i++) {
            $password .= $numbers[random_int(0, strlen($numbers) - 1)];
        }
        
        return $password;
    }
    
    /**
     * Generate memorable password with words and numbers
     */
    public function generateMemorablePassword()
    {
        $adjectives = ['quick', 'bright', 'happy', 'smart', 'strong', 'brave', 'kind', 'cool'];
        $nouns = ['tiger', 'eagle', 'star', 'moon', 'sun', 'wave', 'fire', 'wind'];
        
        $adjective = $adjectives[array_rand($adjectives)];
        $noun = $nouns[array_rand($nouns)];
        $number = random_int(100, 999);
        
        return ucfirst($adjective) . ucfirst($noun) . $number;
    }
    
    /**
     * Generate password and send via WhatsApp
     */
    public function generateAndSendPassword($user, $whatsappService, $type = 'simple')
    {
        try {
            // Generate password based on type
            switch ($type) {
                case 'complex':
                    $password = $this->generateRandomPassword(8);
                    break;
                case 'memorable':
                    $password = $this->generateMemorablePassword();
                    break;
                default:
                    $password = $this->generateSimplePassword(6);
                    break;
            }
            
            // Hash and save password
            $user->password = Hash::make($password);
            $user->save();
            
            // Send WhatsApp message via queue
            $whatsappNumber = $user->whatsapp_number ?: $user->phone;
            
            if ($whatsappNumber) {
                $message = $this->formatPasswordMessage($user, $password);
                $queueId = $whatsappService->queueMessage($whatsappNumber, $message, 'high');
                
                Log::info('Random password generated and queued', [
                    'user_id' => $user->id,
                    'user_email' => $user->email,
                    'whatsapp_number' => $whatsappNumber,
                    'password_type' => $type,
                    'queue_id' => $queueId
                ]);
                
                return [
                    'success' => true,
                    'message' => 'Password berhasil digenerate dan dikirim via WhatsApp',
                    'password_sent' => true
                ];
            } else {
                Log::warning('No WhatsApp number for password sending', [
                    'user_id' => $user->id,
                    'user_email' => $user->email
                ]);
                
                return [
                    'success' => true,
                    'message' => 'Password berhasil digenerate',
                    'password' => $password, // Return password if no WhatsApp
                    'password_sent' => false
                ];
            }
            
        } catch (\Exception $e) {
            Log::error('Failed to generate and send password', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return [
                'success' => false,
                'message' => 'Gagal generate password: ' . $e->getMessage(),
                'password_sent' => false
            ];
        }
    }
    
    /**
     * Generate password and return it without sending WhatsApp
     */
    public function generatePasswordOnly($user, $type = 'simple')
    {
        try {
            // Generate password based on type
            switch ($type) {
                case 'complex':
                    $password = $this->generateRandomPassword(8);
                    break;
                case 'memorable':
                    $password = $this->generateMemorablePassword();
                    break;
                case 'simple':
                default:
                    $password = $this->generateSimplePassword(6);
                    break;
            }
            
            // Hash and save password
            $user->password = Hash::make($password);
            $user->save();
            
            Log::info('Password generated without WhatsApp sending', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'password_type' => $type
            ]);
            
            return [
                'success' => true,
                'password' => $password,
                'message' => 'Password berhasil digenerate'
            ];
            
        } catch (\Exception $e) {
            Log::error('Failed to generate password', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return [
                'success' => false,
                'message' => 'Gagal generate password: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Format WhatsApp message for password
     */
    private function formatPasswordMessage($user, $password)
    {
        $message = "🔐 *PASSWORD LOGIN ANDA* 🔐\n\n";
        $message .= "Halo *{$user->name}*,\n\n";
        $message .= "Password login Anda untuk " . config('event.name') . " " . config('event.year') . " Dashboard:\n\n";
        $message .= "📧 *Email*: {$user->email}\n";
        $message .= "🔑 *Password*: `{$password}`\n\n";
        $message .= "⚠️ *PENTING:*\n";
        $message .= "• Simpan password ini dengan aman\n";
        $message .= "• Jangan bagikan kepada siapa pun\n";
        $message .= "• Anda dapat mengubah password setelah login\n\n";
        $message .= "🔗 *Login*: " . url('/login') . "\n\n";
        $message .= "Jika Anda tidak meminta password ini, hubungi admin segera.\n\n";
        $message .= "Terima kasih! 🏃‍♂️💪";
        
        return $message;
    }
}
