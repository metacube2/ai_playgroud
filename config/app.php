<?php

return [
    'name' => env('APP_NAME', 'GetYourBand'),
    'env' => env('APP_ENV', 'production'),
    'debug' => env('APP_DEBUG', false),
    'url' => env('APP_URL', 'http://localhost'),

    'timezone' => 'Europe/Zurich',
    'locale' => 'de_CH',

    'features' => [
        'email_verification' => env('REQUIRE_EMAIL_VERIFICATION', true),
        'band_approval' => env('REQUIRE_BAND_APPROVAL', true),
        'reviews' => env('ENABLE_REVIEWS', true),
        'payment' => env('PAYMENT_ENABLED', false),
    ],

    'upload' => [
        'max_size' => env('MAX_UPLOAD_SIZE', 5242880), // 5MB
        'allowed_images' => explode(',', env('ALLOWED_IMAGE_TYPES', 'jpg,jpeg,png,webp')),
        'allowed_videos' => explode(',', env('ALLOWED_VIDEO_TYPES', 'mp4,webm')),
    ],

    'pagination' => [
        'per_page' => 12,
    ],
];
