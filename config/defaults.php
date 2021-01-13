<?php

use Illuminate\Support\Str;

return [
    'users' => [
        'credentials' => [
            'name' => 'Thavarshan Thayananthajothy',
            'username' => 'Thavarshan',
            'email' => 'tjthavarshan@gmail.com',
            'password' => '$2y$10$8pDzMlxQjpSLTTdHlyXE6e8oEpg3TWtEu7qIXbERgtcAbK1.zZUI6', // MyTopSecretPassword
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
            'settings' => [],
            'locked' => false,
        ],

        'permissions' => [
            'create',
            'read',
            'update',
            'delete',
        ],
    ],
];
