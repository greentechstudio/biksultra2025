<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Event Configuration
    |--------------------------------------------------------------------------
    |
    | This configuration contains all event-related information that can be
    | easily changed by modifying the .env file. This allows the same
    | codebase to be used for different events without hardcoded values.
    |
    */

    // Basic Event Information
    'name' => env('EVENT_NAME', 'Event Name'),
    'full_name' => env('EVENT_FULL_NAME', 'Full Event Name'),
    'year' => env('EVENT_YEAR', date('Y')),
    'tagline' => env('EVENT_TAGLINE', 'Event Tagline'),
    'short_name' => env('EVENT_SHORT_NAME', 'Event'),
    
    // Dynamic App Name
    'app_name' => env('EVENT_NAME', 'Event Name') . ' ' . env('EVENT_YEAR', date('Y')),

    // Organizer Information
    'organizer' => [
        'name' => env('EVENT_ORGANIZER', 'Event Organizer'),
        'short' => env('EVENT_ORGANIZER_SHORT', 'ORG'),
        'region' => env('EVENT_ORGANIZER_REGION', 'Regional Office'),
    ],

    // Location Information
    'location' => [
        'province' => env('EVENT_PROVINCE', 'Province'),
        'city' => env('EVENT_CITY', 'City'),
        'venue_main' => env('EVENT_VENUE_MAIN', 'Main Venue'),
        'venue_run' => env('EVENT_VENUE_RUN', 'Run Venue'),
    ],

    // Date & Time Information
    'date' => [
        'month' => env('EVENT_MONTH', 'Month'),
        'display' => env('EVENT_DATE_DISPLAY', 'Date Display'),
    ],

    // Content & Description
    'content' => [
        'description' => env('EVENT_DESCRIPTION', 'Event description goes here.'),
        'description_short' => env('EVENT_DESCRIPTION_SHORT', 'Short event description.'),
    ],

    // SEO & Meta Information
    'seo' => [
        'keywords' => env('EVENT_KEYWORDS', 'event, keywords'),
        'meta_description' => env('EVENT_META_DESCRIPTION', 'Event meta description for SEO.'),
    ],

    // Dynamic Titles
    'titles' => [
        'main' => env('EVENT_NAME', 'Event Name') . ' ' . env('EVENT_YEAR', date('Y')) . ' | ' . env('EVENT_FULL_NAME', 'Full Event Name') . ' - ' . env('EVENT_TAGLINE', 'Event Tagline'),
        'payment_success' => 'Pembayaran Berhasil - ' . env('EVENT_NAME', 'Event Name'),
        'payment_failed' => 'Pembayaran Gagal - ' . env('EVENT_NAME', 'Event Name'),
    ],

    // Dynamic Content Pieces
    'hero' => [
        'title' => env('EVENT_FULL_NAME', 'Full Event Name') . ' ' . env('EVENT_YEAR', date('Y')),
        'subtitle' => 'program tahunan yang diselenggarakan oleh ' . env('EVENT_ORGANIZER', 'Event Organizer') . ' (' . env('EVENT_ORGANIZER_SHORT', 'ORG') . ')',
        'location' => env('EVENT_VENUE_MAIN', 'Main Venue') . ', ' . env('EVENT_CITY', 'City') . ', ' . env('EVENT_PROVINCE', 'Province'),
    ],

    // Partner Information
    'partners' => [
        'title' => 'Partner ' . env('EVENT_FULL_NAME', 'Full Event Name') . ' ' . env('EVENT_YEAR', date('Y')),
    ],

    // Schedule Information
    'schedule' => [
        'title' => 'Jadwal Kegiatan ' . env('EVENT_NAME', 'Event Name') . ' ' . env('EVENT_YEAR', date('Y')),
        'description' => 'beberapa acara dalam rangka ' . strtolower(env('EVENT_FULL_NAME', 'full event name')) . ' ' . env('EVENT_YEAR', date('Y')) . '.',
        'main_event_description' => 'Dalam rangka memperingati ' . env('EVENT_FULL_NAME', 'Full Event Name') . ' (' . env('EVENT_ORGANIZER_SHORT', 'ORG') . ') ' . env('EVENT_YEAR', date('Y')) . ', ' . env('EVENT_ORGANIZER', 'Event Organizer') . ' (' . env('EVENT_ORGANIZER_SHORT', 'ORG') . ') bersama Industri Jasa Keuangan menyelenggarakan ' . env('EVENT_SHORT_NAME', 'Event') . ' ' . env('EVENT_YEAR', date('Y')) . ' .',
    ],

];