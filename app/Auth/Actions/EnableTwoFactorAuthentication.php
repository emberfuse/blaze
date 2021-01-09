<?php

namespace App\Auth\Actions;

use App\Models\User;
use App\Codes\RecoveryCode;
use Illuminate\Support\Collection;
use App\Contracts\Auth\TwoFactorAuthenticator;
use App\Events\TwoFactorAuthenticationEnabled;

class EnableTwoFactorAuthentication
{
    /**
     * The two factor authentication provider.
     *
     * @var \App\Contracts\Auth\TwoFactorAuthenticator
     */
    protected $authenticator;

    /**
     * Number of recovery codes to generate.
     *
     * @var int
     */
    protected $recoveryCodeCount = 8;

    /**
     * Create a new action instance.
     *
     * @param \App\Contracts\Auth\TwoFactorAuthenticator $authenticator
     * @param int                                        $recoveryCodeCount
     *
     * @return void
     */
    public function __construct(TwoFactorAuthenticator $authenticator, ?int $recoveryCodeCount = null)
    {
        $this->authenticator = $authenticator;

        if (! is_null($recoveryCodeCount)) {
            $this->recoveryCodeCount = $recoveryCodeCount;
        }
    }

    /**
     * Enable two factor authentication for the user.
     *
     * @param \App\Models\User $user
     *
     * @return void
     */
    public function __invoke(User $user): void
    {
        $user->forceFill([
            'two_factor_secret' => encrypt($this->authenticator->generateSecretKey()),
            'two_factor_recovery_codes' => encrypt($this->generateRecoveryCodes()),
        ])->save();

        TwoFactorAuthenticationEnabled::dispatch($user);
    }

    /**
     * Generate recovery codes for user.
     *
     * @return string
     */
    protected function generateRecoveryCodes(): string
    {
        return json_encode(Collection::times(
            $this->recoveryCodeCount,
            fn () => RecoveryCode::generate()
        )->all());
    }
}
