<?php

namespace App\Auth\Actions;

use App\Contracts\Auth\UpdatesUserPasswords;
use App\Auth\Actions\Concerns\UpdatesPassword;
use Illuminate\Contracts\Auth\Authenticatable;

class UpdateUserPassword implements UpdatesUserPasswords
{
    use UpdatesPassword;

    /**
     * update the user's password.
     *
     * @param \Illuminate\Contracts\Auth\Authenticatable $user
     * @param array                                      $data
     *
     * @return void
     */
    public function update(Authenticatable $user, array $data): void
    {
        $this->updatePassword($user, $data['password']);
    }
}
