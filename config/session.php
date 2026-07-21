<?php

return [
    'driver' => env('SESSION_DRIVER', 'database'),
    'lifetime' => env('SESSION_LIFETIME', 120),
    'expire_on_close' => env('SESSION_EXPIRE_ON_CLOSE', false),
    'encrypt' => env('SESSION_ENCRYPT', false),
    'files' => storage_path('framework/sessions'),
    'connection' => env('SESSION_CONNECTION'),
    'table' => env('SESSION_TABLE', 'sessions'),
    'store' => env('SESSION_STORE'),
    'lottery' => [2, 100],
    'cookie' => env('SESSION_COOKIE', 'nutrishare_session'),
    'path' => env('SESSION_PATH', '/'),
    'domain' => env('SESSION_DOMAIN'),

    /*
    |--------------------------------------------------------------------------
    | SECURITY: Session Hijacking Prevention (Module 2 - OWASP A7)
    |--------------------------------------------------------------------------
    | HttpOnly: Prevents JavaScript from accessing the session cookie (XSS mitigation)
    | Secure: Cookie only sent over HTTPS connections
    | SameSite: Strict prevents CSRF by not sending cookie on cross-site requests
    */
    'http_only' => env('SESSION_HTTP_ONLY', true),
    'secure' => env('SESSION_SECURE_COOKIE', true),
    'same_site' => env('SESSION_SAME_SITE', 'strict'),
    'partitioned' => env('SESSION_PARTITIONED_COOKIE', false),
];
