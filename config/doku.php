<?php

return [
    'client_id' => env('DOKU_CLIENT_ID'),
    'secret_key' => env('DOKU_SECRET_KEY'),
    'base_url' => env('DOKU_BASE_URL', 'https://api-sandbox.doku.com'),
    'merchant_name' => env('DOKU_MERCHANT_NAME', env('APP_NAME', 'Sadita System')),
    'payment_due_minutes' => (int) env('DOKU_PAYMENT_DUE_MINUTES', 60),
    'notification_url' => env('DOKU_NOTIFICATION_URL'),
    'return_url' => env('DOKU_RETURN_URL'),
    'default_city' => env('DOKU_DEFAULT_CITY', 'Padang'),
    'default_postal_code' => env('DOKU_DEFAULT_POSTAL_CODE', '25100'),
    'default_country_code' => env('DOKU_DEFAULT_COUNTRY_CODE', 'IDN'),
    'payment_methods' => [
        ['code' => 'ALL', 'label' => 'Semua metode DOKU', 'description' => 'Biarkan DOKU menampilkan semua metode yang aktif.'],
    ],
];
