<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckFeatureEnabled
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $feature): Response
    {
        // Check if the feature is enabled
        if (!config("features.{$feature}.enabled")) {
            // Return JSON response for API requests
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'error' => config("features.disabled_messages.{$feature}", config('features.disabled_messages.default')),
                    'feature_disabled' => true
                ], 403);
            }

            // Redirect with error message for web requests
            return redirect()->back()->withErrors([
                'feature' => config("features.disabled_messages.{$feature}", config('features.disabled_messages.default'))
            ]);
        }

        return $next($request);
    }
}