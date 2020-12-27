<?php

namespace App\Auth\Actions;

use App\Models\User;

class DisableTwoFactorAuthentication
{
    /**
     * Disable two factor authentication for the user.
     *
     * @param \App\Models\User $user
     *
     * @return void
     */
    public function __invoke(User $user)
    {
        $user->forceFill([
            'two_factor_enabled' => false,
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
        ])->save();
    }
}
