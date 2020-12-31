<?php

use App\Models\User;
use App\Rules\PasswordRule;
use Illuminate\Validation\Rule;

return [
    /*
     * User Login Validation Rules.
     */
    'login' => [
        'email' => ['required', 'string', 'email'],
        'password' => ['required', 'string'],
    ],

    /*
     * Use Registration Validation Rules.
     */
    'register' => [
        'name' => ['required', 'string', 'max:255'],
        'email' => [
            'required',
            'string',
            'email',
            'max:255',
            Rule::unique(User::class),
        ],
        'password' => ['required', 'string', new PasswordRule()],
    ],

    /*
     * Use Profile Information Validation Rules.
     */
    'update_profile' => [
        'photo' => ['sometimes', 'image', 'max:1024'],
        'name' => ['required', 'string', 'max:255'],
        'username' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email'],
    ],
];
