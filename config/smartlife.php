<?php

return [
    /*
    |--------------------------------------------------------------------------
    | SmartLife ERP API Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for SmartLife ERP integration
    |
    */

    'api_url' => env('SMARTLIFE_API_URL', 'https://smarterp.top/api/v3'),

    'credentials' => [
        'login_company' => env('SMARTLIFE_COMPANY', 'company'),
        'username' => env('SMARTLIFE_USERNAME', 'username'),
        'password' => env('SMARTLIFE_PASSWORD', 'password'),
    ],

    // Enable or disable SmartLife syncing from environment
    'sync_enabled' => env('SMARTLIFE_SYNC_ENABLED', false),

    'customer_id' => env('SMARTLIFE_CUSTOMER_ID', '1'),

    // Cache token for 24 hours (in seconds)
    'token_cache_ttl' => env('SMARTLIFE_TOKEN_TTL', 86400),

    // Payment account mapping for "Paid" status
    'payment_account_id' => env('SMARTLIFE_PAYMENT_ACCOUNT', '1020100001'),
];
