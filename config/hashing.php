<?php

return [
    'default' => env('HASHING_DRIVER', 'bcrypt'),

    /*
    |--------------------------------------------------------------------------
    | SECURITY: Weak Password Prevention (Module 2 - OWASP A2)
    |--------------------------------------------------------------------------
    | Bcrypt is used with 12 rounds for strong one-way hashing of passwords.
    | This ensures passwords cannot be reversed and brute-force attacks are
    | computationally expensive.
    */
    'bcrypt' => [
        'rounds' => env('BCRYPT_ROUNDS', 12),
        'verify' => true,
    ],

    'argon' => [
        'memory' => 65536,
        'threads' => 1,
        'time' => 4,
        'verify' => true,
    ],

    'rehash_on_login' => true,
];
