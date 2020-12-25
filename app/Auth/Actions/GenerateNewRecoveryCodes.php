<?php

namespace App\Auth\Actions;

use App\Models\User;
use App\Codes\RecoveryCode;
use Illuminate\Support\Collection;

class GenerateNewRecoveryCodes
{
    /**
     * Generate new recovery codes for the user.
     *
     * @param \App\Models\User $user
     *
     * @return void
     */
    public function __invoke(User $user)
    {
        $user->forceFill([
            'two_factor_recovery_codes' => encrypt(json_encode(Collection::times(8, function () {
                return RecoveryCode::generate();
            })->all())),
        ])->save();
    }
}
