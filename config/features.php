<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Feature Toggles
    |--------------------------------------------------------------------------
    |
    | Control which registration features are enabled or disabled.
    | Set to false to disable a feature without removing the code.
    | This allows easy reactivation in the future.
    |
    */

    'individual_registration' => [
        'enabled' => env('FEATURE_INDIVIDUAL_REGISTRATION', true),
        'name' => 'Individual Registration',
        'description' => 'Allow individual race registration'
    ],

    'collective_registration' => [
        'enabled' => env('FEATURE_COLLECTIVE_REGISTRATION', false), // DISABLED
        'name' => 'Collective Registration', 
        'description' => 'Allow collective/group race registration'
    ],

    'wakaf_registration' => [
        'enabled' => env('FEATURE_WAKAF_REGISTRATION', false), // DISABLED
        'name' => 'Wakaf Registration',
        'description' => 'Allow wakaf donation registration'
    ],

    /*
    |--------------------------------------------------------------------------
    | Feature Messages
    |--------------------------------------------------------------------------
    |
    | Custom messages to show when features are disabled
    |
    */

    'disabled_messages' => [
        'collective_registration' => 'Maaf, pendaftaran kolektif sedang tidak tersedia. Silakan gunakan pendaftaran individual.',
        'wakaf_registration' => 'Maaf, fitur wakaf sedang dalam maintenance. Terima kasih atas pengertiannya.',
        'default' => 'Fitur ini sedang tidak tersedia sementara waktu.'
    ]
];