<?php

use Illuminate\Support\Str;

return [
    'users' => [
        'credentials' => [
            'name' => 'Thavarshan Thayananthajothy',
            'username' => 'Thavarshan',
            'email' => 'tjthavarshan@gmail.com',
            'password' => '$2y$04$uitMi8kxfAHXz.aAWujG/.SeRuXwkVMuLJP/xLpLeWgyhZ5OUDzf6', // alphaxion77
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
