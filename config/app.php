<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Application Settings
    |--------------------------------------------------------------------------
    */
    'name' => getenv('APP_NAME', 'PAGASA Cyclone Monitoring'),
    'env' => getenv('APP_ENV', 'production'),
    'debug' => getenv('APP_DEBUG', false),
    'url' => getenv('APP_URL', 'http://localhost'),
    'timezone' => 'Asia/Manila',
    'locale' => 'en',

    /*
    |--------------------------------------------------------------------------
    | API Configuration
    |--------------------------------------------------------------------------
    */
    'api' => [
        'noaa' => [
            'key' => getenv('NOAA_API_KEY'),
            'endpoint' => 'https://api.noaa.gov/v1',
        ],
        'pagasa' => [
            'key' => getenv('PAGASA_API_KEY'),
            'endpoint' => 'https://api.pagasa.gov.ph/v1',
        ],
        'google_maps' => [
            'key' => getenv('GOOGLE_MAPS_API_KEY'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Security Settings
    |--------------------------------------------------------------------------
    */
    'jwt' => [
        'secret' => getenv('JWT_SECRET'),
        'expiration' => 3600, // 1 hour
        'refresh_expiration' => 604800, // 1 week
    ],

    /*
    |--------------------------------------------------------------------------
    | Notification Settings
    |--------------------------------------------------------------------------
    */
    'notifications' => [
        'email' => [
            'from_address' => getenv('MAIL_FROM_ADDRESS'),
            'from_name' => getenv('MAIL_FROM_NAME'),
        ],
        'sms' => [
            'provider' => getenv('SMS_PROVIDER'),
            'api_key' => getenv('SMS_API_KEY'),
            'from' => getenv('SMS_FROM'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Session Configuration
    |--------------------------------------------------------------------------
    */
    'session' => [
        'driver' => getenv('SESSION_DRIVER', 'file'),
        'lifetime' => getenv('SESSION_LIFETIME', 120),
        'expire_on_close' => false,
        'encrypt' => false,
        'cookie' => 'pagasa_session',
    ],

    /*
    |--------------------------------------------------------------------------
    | Logging Configuration
    |--------------------------------------------------------------------------
    */
    'logging' => [
        'default' => 'daily',
        'channels' => [
            'daily' => [
                'driver' => 'daily',
                'path' => __DIR__ . '/../storage/logs/app.log',
                'level' => 'debug',
                'days' => 14,
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache Configuration
    |--------------------------------------------------------------------------
    */
    'cache' => [
        'default' => 'file',
        'stores' => [
            'file' => [
                'driver' => 'file',
                'path' => __DIR__ . '/../storage/framework/cache',
            ],
            'redis' => [
                'driver' => 'redis',
                'connection' => 'default',
            ],
        ],
    ],
]; 