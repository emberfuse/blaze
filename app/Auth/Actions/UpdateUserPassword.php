<?php

namespace App\Auth\Actions;

use App\Contracts\Auth\UpdatesUserPasswords;
use Illuminate\Contracts\Auth\Authenticatable;
use App\Auth\Actions\Traits\UpdatesUserPasswords as PasswordUpdator;

class UpdateUserPassword implements UpdatesUserPasswords
{
    use PasswordUpdator;

    /**
     * Update the user's password.
     *
     * @param \Illuminate\Contracts\Auth\Authenticatable $user
     * @param array                                      $data
     *
     * @return void
     */
    public function update(Authenticatable $user, array $data): void
    {
        $this->updatePassword($user, $data['password'], true);
    }
}
