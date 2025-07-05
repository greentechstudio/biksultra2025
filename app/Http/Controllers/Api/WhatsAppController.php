<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppController extends Controller
{
    public function checkNumber(Request $request)
    {
        try {
            $request->validate([
                'number' => 'required|string|min:10|max:15'
            ]);

            $phoneNumber = $this->formatPhoneNumber($request->number);
            
            Log::info('WhatsApp Validation Request', [
                'original_number' => $request->number,
                'formatted_number' => $phoneNumber,
                'ip' => $request->ip()
            ]);
            
            // WhatsApp number check API
            $apiKey = env('WHATSAPP_API_KEY', 'tZiKYy1sHXasOj0hDGZnRfAnAYo2Ec');
            $sender = env('WHATSAPP_SENDER', '628114040707');
            $apiUrl = 'https://wamd.system112.org/check-number';
            
            $fullUrl = $apiUrl . '?' . http_build_query([
                'api_key' => $apiKey,
                'sender' => $sender,
                'number' => $phoneNumber
            ]);
            
            Log::info('Making API request to', ['url' => $fullUrl]);
            
            $response = Http::timeout(15)->get($apiUrl, [
                'api_key' => $apiKey,
                'sender' => $sender,
                'number' => $phoneNumber
            ]);

            Log::info('WhatsApp API Raw Response', [
                'phone' => $phoneNumber,
                'status_code' => $response->status(),
                'headers' => $response->headers(),
                'body' => $response->body()
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                // Log the response for debugging
                Log::info('WhatsApp API Parsed Response', [
                    'phone' => $phoneNumber,
                    'parsed_data' => $data
                ]);
                
                // Check if the response indicates the number has WhatsApp
                // API Response format: {"status": true, "data": {"jid": "...", "exists": true}}
                $hasWhatsapp = false;
                $message = 'Nomor tidak terdaftar di WhatsApp';
                
                // Handle the actual API response format
                if (isset($data['status']) && $data['status'] === true) {
                    // API returns status: true for successful requests
                    if (isset($data['data']['exists']) && $data['data']['exists'] === true) {
                        $hasWhatsapp = true;
                        $message = 'Nomor WhatsApp valid dan aktif';
                        Log::info('WhatsApp number validated successfully', ['phone' => $phoneNumber]);
                    } else {
                        $hasWhatsapp = false;
                        $message = 'Nomor tidak terdaftar di WhatsApp';
                        Log::info('WhatsApp number not found', [
                            'phone' => $phoneNumber,
                            'data_exists' => $data['data']['exists'] ?? 'missing'
                        ]);
                    }
                } else {
                    // Handle case where status is false or missing
                    $hasWhatsapp = false;
                    $message = isset($data['message']) ? $data['message'] : 'Nomor tidak terdaftar di WhatsApp';
                    Log::warning('WhatsApp API returned unexpected status', [
                        'phone' => $phoneNumber,
                        'status' => $data['status'] ?? 'missing',
                        'full_response' => $data
                    ]);
                }

                return response()->json([
                    'success' => true,
                    'hasWhatsapp' => $hasWhatsapp,
                    'number' => $phoneNumber,
                    'message' => $message,
                    'debug_info' => [
                        'api_url' => $apiUrl,
                        'formatted_number' => $phoneNumber,
                        'original_number' => $request->number,
                        'response_status' => $data['status'] ?? 'missing',
                        'data_exists' => $data['data']['exists'] ?? 'missing'
                    ],
                    'raw_response' => $data // For debugging (remove in production)
                ]);
            } else {
                Log::error('WhatsApp API HTTP Error', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'phone' => $phoneNumber,
                    'headers' => $response->headers()
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghubungi service validasi WhatsApp. Silakan coba lagi.',
                    'debug_info' => [
                        'http_status' => $response->status(),
                        'api_url' => $apiUrl
                    ]
                ], 500);
            }
        } catch (\Illuminate\Http\Client\RequestException $e) {
            Log::error('WhatsApp API Request Exception', [
                'error' => $e->getMessage(),
                'number' => $phoneNumber,
                'response' => $e->response ? $e->response->body() : 'No response'
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan koneksi saat validasi nomor. Silakan coba lagi.',
                'debug_info' => [
                    'error_type' => 'request_exception',
                    'error_message' => $e->getMessage()
                ]
            ], 500);
        } catch (\Exception $e) {
            Log::error('WhatsApp Validation General Exception', [
                'error' => $e->getMessage(),
                'number' => $phoneNumber,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat validasi nomor. Silakan coba lagi.',
                'debug_info' => [
                    'error_type' => 'general_exception',
                    'error_message' => $e->getMessage()
                ]
            ], 500);
        }
    }

    public function sendMessage(Request $request)
    {
        try {
            $request->validate([
                'number' => 'required|string|min:10|max:15',
                'message' => 'required|string'
            ]);

            $phoneNumber = $this->formatPhoneNumber($request->number);
            $message = $request->message;
            
            Log::info('WhatsApp Send Message Request', [
                'number' => $phoneNumber,
                'message' => substr($message, 0, 100) . '...',
                'ip' => $request->ip()
            ]);
            
            // WhatsApp send message API
            $apiKey = env('WHATSAPP_API_KEY', 'tZiKYy1sHXasOj0hDGZnRfAnAYo2Ec');
            $sender = env('WHATSAPP_SENDER', '628114040707');
            $apiUrl = 'https://wamd.system112.org/send-message';
            
            $response = Http::timeout(30)->post($apiUrl, [
                'api_key' => $apiKey,
                'sender' => $sender,
                'number' => $phoneNumber,
                'message' => $message
            ]);

            Log::info('WhatsApp Send Message API Response', [
                'phone' => $phoneNumber,
                'status_code' => $response->status(),
                'body' => $response->body()
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                // Check if message was sent successfully
                if (isset($data['status']) && $data['status'] === true) {
                    Log::info('WhatsApp message sent successfully', [
                        'phone' => $phoneNumber,
                        'message_id' => $data['data']['id'] ?? 'unknown'
                    ]);
                    
                    return response()->json([
                        'success' => true,
                        'message' => 'Pesan WhatsApp berhasil dikirim',
                        'number' => $phoneNumber,
                        'data' => $data
                    ]);
                } else {
                    Log::warning('WhatsApp message send failed', [
                        'phone' => $phoneNumber,
                        'response' => $data
                    ]);
                    
                    return response()->json([
                        'success' => false,
                        'message' => 'Gagal mengirim pesan WhatsApp: ' . ($data['message'] ?? 'Unknown error'),
                        'data' => $data
                    ], 400);
                }
            } else {
                Log::error('WhatsApp Send Message HTTP Error', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'phone' => $phoneNumber
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghubungi service WhatsApp',
                    'http_status' => $response->status()
                ], 500);
            }
        } catch (\Exception $e) {
            Log::error('WhatsApp Send Message Exception', [
                'error' => $e->getMessage(),
                'number' => $request->number ?? 'unknown',
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengirim pesan WhatsApp',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function formatPhoneNumber($phoneNumber)
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
}
