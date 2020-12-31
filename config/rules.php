<?php

return [
    /*
     * Use Login Validation Rules.
     */
    'login' => [
        'email' => ['required', 'string', 'email'],
        'password' => ['required', 'string'],
    ],
];
