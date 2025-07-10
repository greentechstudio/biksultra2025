<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class PreventPriceManipulation
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Check for price manipulation attempts
        $this->detectSuspiciousActivity($request);
        
        // Note: Rate limiting removed to allow legitimate bulk registrations
        // (e.g., schools, companies, families using same network)
        
        return $next($request);
    }
    
    /**
     * Detect suspicious activity in requests
     */
    private function detectSuspiciousActivity(Request $request)
    {
        $suspiciousParams = [
            'price', 'amount', 'cost', 'fee', 'payment_amount',
            'ticket_price', 'registration_fee', 'total', 'subtotal',
            'discount', 'coupon', 'promo_code', 'voucher',
            'admin', 'admin_price', 'override_price', 'custom_price'
        ];
        
        $allInput = $request->all();
        $foundSuspicious = [];
        
        foreach ($suspiciousParams as $param) {
            if (array_key_exists($param, $allInput)) {
                $foundSuspicious[$param] = $allInput[$param];
            }
        }
        
        if (!empty($foundSuspicious)) {
            Log::warning('SECURITY: Price manipulation attempt detected', [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'suspicious_params' => $foundSuspicious,
                'email' => $request->input('email'), // Track email for investigation
                'timestamp' => now()->toDateTimeString()
            ]);
            
            // Track repeated attempts from same IP for monitoring (but don't block)
            $key = 'suspicious_activity:' . $request->ip();
            $attempts = Cache::get($key, 0);
            Cache::put($key, $attempts + 1, 3600); // 1 hour
            
            if ($attempts >= 5) {
                Log::alert('SECURITY: Multiple price manipulation attempts from IP', [
                    'ip' => $request->ip(),
                    'attempts' => $attempts + 1,
                    'user_agent' => $request->userAgent(),
                    'note' => 'This IP has made multiple suspicious attempts - consider investigation'
                ]);
                
                // Don't block automatically - just flag for manual review
                // In high-security environments, you might want to add additional verification
            }
        }
    }
}
