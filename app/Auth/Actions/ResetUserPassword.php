<?php

namespace App\Auth\Actions;

use App\Contracts\Auth\ResetsUserPasswords;
use App\Auth\Actions\Concerns\UpdatesPassword;
use Illuminate\Contracts\Auth\Authenticatable;

class ResetUserPassword implements ResetsUserPasswords
{
    use UpdatesPassword;

    /**
     * Reset the user's forgotten password.
     *
     * @param \Illuminate\Contracts\Auth\Authenticatable $user
     * @param array                                      $data
     *
     * @return void
     */
    public function reset(Authenticatable $user, array $data): void
    {
        $this->updatePassword($user, $data['password']);
    }
}
