<?php

namespace App\Auth\Actions;

use App\Contracts\Auth\ConfirmsPasswords;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Contracts\Auth\Authenticatable;

class ConfirmPassword implements ConfirmsPasswords
{
    /**
     * Confirm that the given password is valid for the given user.
     *
     * @param \Illuminate\Contracts\Auth\StatefulGuard   $guard
     * @param \Illuminate\Contracts\Auth\Authenticatable $user
     * @param string|null                                $password
     *
     * @return bool
     */
    public function confirm(StatefulGuard $guard, Authenticatable $user, ?string $password = null): bool
    {
        $username = config('auth.credentials.username');

        return $guard->validate([
            $username => $user->{$username},
            'password' => $password,
        ]);
    }
}
