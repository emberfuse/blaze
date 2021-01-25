<?php

use Illuminate\Support\Str;

return [
    'users' => [
        'credentials' => [
            'name' => 'Thavarshan Thayananthajothy',
            'username' => 'Thavarshan',
            'email' => 'tjthavarshan@gmail.com',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
            'settings' => [],
            'locked' => false,
            'profile_photo_path' => null,
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
        ],
    ],

    'api' => [
        'permissions' => [
            'create',
            'read',
            'update',
            'delete',
        ],
    ],
];
