<?php

namespace App\Auth\Actions\Traits;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Auth\Authenticatable;

trait UpdatesUserPasswords
{
    /**
     * Update given user password field.
     *
     * @param \Illuminate\Contracts\Auth\Authenticatable $user
     * @param string                                     $password
     *
     * @return void
     */
    protected function updatePassword(Authenticatable $user, string $password): void
    {
        $user->forceFill([
            'password' => Hash::make($password),
            'remember_token' => Str::random(60),
        ])->save();
    }
}
