<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
            'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
            'timezone' => \App\Http\Middleware\SetTimezone::class,
            'prevent.price.manipulation' => \App\Http\Middleware\PreventPriceManipulation::class,
        ]);
        
        // Add timezone middleware globally
        $middleware->append(\App\Http\Middleware\SetTimezone::class);
        
        // Add price manipulation detection to registration endpoints only
        // (No rate limiting to allow legitimate bulk registrations)
        $middleware->api(prepend: [
            \App\Http\Middleware\PreventPriceManipulation::class,
        ]);
        
        // Exclude webhook routes from CSRF verification
        $middleware->validateCsrfTokens(except: [
            'api/xendit/webhook',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
