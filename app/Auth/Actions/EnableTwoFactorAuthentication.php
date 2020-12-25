<?php

namespace App\Auth\Actions;

use App\Models\User;
use App\Codes\RecoveryCode;
use Illuminate\Support\Collection;
use App\Contracts\Auth\TwoFactorAuthenticator;

class EnableTwoFactorAuthentication
{
    /**
     * The two factor authentication authenticator.
     *
     * @var \App\Contracts\Auth\TwoFactorAuthenticator
     */
    protected TwoFactorAuthenticator $authenticator;

    /**
     * Create a new action instance.
     *
     * @param \App\Contracts\Auth\TwoFactorAuthenticator $authenticator
     *
     * @return void
     */
    public function __construct(TwoFactorAuthenticator $authenticator)
    {
        $this->authenticator = $authenticator;
    }

    /**
     * Enable two factor authentication for the user.
     *
     * @param \App\Models\User $user
     *
     * @return void
     */
    public function __invoke(User $user)
    {
        $user->forceFill([
            'two_factor_enabled' => true,
            'two_factor_secret' => encrypt($this->authenticator->generateSecretKey()),
            'two_factor_recovery_codes' => encrypt(json_encode(Collection::times(8, function () {
                return RecoveryCode::generate();
            })->all())),
        ])->save();
    }
}
