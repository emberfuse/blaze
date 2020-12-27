<?php

namespace App\Auth\Actions\Concerns;

use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Auth\Authenticatable;

trait UpdatesPassword
{
    /**
     * Update given user password with given password.
     *
     * @param \Illuminate\Contracts\Auth\Authenticatable $user
     * @param string                                     $password
     *
     * @return void
     */
    protected function updatePassword(Authenticatable $user, string $password): void
    {
        $user->forceFill(['password' => Hash::make($password)])->save();
    }
}
