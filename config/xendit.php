<?php

return [
    'base_url' => env('XENDIT_BASE_URL', 'https://api.xendit.co'),
    'public_key' => env('XENDIT_PUBLIC_KEY'),
    'secret_key' => env('XENDIT_SECRET_KEY'),
    'webhook_token' => env('XENDIT_WEBHOOK_TOKEN'),
    'environment' => env('XENDIT_ENVIRONMENT', 'test'), // test or live
    
    // Payment settings
    'registration_fee' => env('REGISTRATION_FEE', 150000), // Default Rp 150,000
    'invoice_duration' => 86400, // 24 hours in seconds
    
    // Callback URLs
    'success_redirect_url' => env('APP_URL') . '/payment/success',
    'failure_redirect_url' => env('APP_URL') . '/payment/failed',
    'webhook_url' => env('APP_URL') . '/api/xendit/webhook',
];
