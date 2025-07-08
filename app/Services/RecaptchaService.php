<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RecaptchaService
{
    private $secretKey;
    private $siteKey;
    private $verifyUrl;
    
    public function __construct()
    {
        $this->secretKey = config('services.recaptcha.secret_key');
        $this->siteKey = config('services.recaptcha.site_key');
        $this->verifyUrl = 'https://www.google.com/recaptcha/api/siteverify';
    }
    
    /**
     * Verify reCAPTCHA response
     */
    public function verify($recaptchaResponse, $userIp = null)
    {
        try {
            if (empty($recaptchaResponse)) {
                return [
                    'success' => false,
                    'message' => 'reCAPTCHA response is required'
                ];
            }
            
            $response = Http::asForm()->post($this->verifyUrl, [
                'secret' => $this->secretKey,
                'response' => $recaptchaResponse,
                'remoteip' => $userIp ?: request()->ip()
            ]);
            
            $result = $response->json();
            
            Log::info('reCAPTCHA verification attempt', [
                'success' => $result['success'] ?? false,
                'score' => $result['score'] ?? null,
                'action' => $result['action'] ?? null,
                'hostname' => $result['hostname'] ?? null,
                'challenge_ts' => $result['challenge_ts'] ?? null,
                'error_codes' => $result['error-codes'] ?? null
            ]);
            
            if ($result['success']) {
                // For reCAPTCHA v3, check score (0.0 to 1.0)
                $score = $result['score'] ?? 0.5;
                $minScore = 0.5; // Minimum acceptable score
                
                if ($score >= $minScore) {
                    return [
                        'success' => true,
                        'score' => $score,
                        'message' => 'reCAPTCHA verification successful'
                    ];
                } else {
                    return [
                        'success' => false,
                        'score' => $score,
                        'message' => 'reCAPTCHA score too low (possible bot activity)'
                    ];
                }
            } else {
                $errorCodes = $result['error-codes'] ?? ['unknown-error'];
                $errorMessage = $this->getErrorMessage($errorCodes);
                
                return [
                    'success' => false,
                    'message' => $errorMessage,
                    'error_codes' => $errorCodes
                ];
            }
            
        } catch (\Exception $e) {
            Log::error('reCAPTCHA verification failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return [
                'success' => false,
                'message' => 'reCAPTCHA verification failed: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Get site key for frontend
     */
    public function getSiteKey()
    {
        return $this->siteKey;
    }
    
    /**
     * Get human-readable error message
     */
    private function getErrorMessage($errorCodes)
    {
        $messages = [
            'missing-input-secret' => 'The secret parameter is missing',
            'invalid-input-secret' => 'The secret parameter is invalid or malformed',
            'missing-input-response' => 'The response parameter is missing',
            'invalid-input-response' => 'The response parameter is invalid or malformed',
            'bad-request' => 'The request is invalid or malformed',
            'timeout-or-duplicate' => 'The response is no longer valid: either is too old or has been used previously'
        ];
        
        $errorMessages = [];
        foreach ($errorCodes as $code) {
            $errorMessages[] = $messages[$code] ?? 'Unknown error: ' . $code;
        }
        
        return implode(', ', $errorMessages);
    }
    
    /**
     * Generate reCAPTCHA HTML for frontend
     */
    public function renderScript()
    {
        return "
        <script src=\"https://www.google.com/recaptcha/api.js?render={$this->siteKey}\"></script>
        <script>
        function executeRecaptcha(action) {
            return new Promise((resolve, reject) => {
                grecaptcha.ready(function() {
                    grecaptcha.execute('{$this->siteKey}', {action: action}).then(function(token) {
                        resolve(token);
                    }).catch(function(error) {
                        reject(error);
                    });
                });
            });
        }
        </script>
        ";
    }
}
