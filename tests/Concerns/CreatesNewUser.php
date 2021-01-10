<?php

namespace Tests\Concerns;

use Tests\TestCase;
use App\Models\User;

trait CreatesNewUser
{
    /**
     * Actions to run after creating fake user.
     *
     * @var \Closure
     */
    protected static $afterCreatingUser;

    /**
     * Create and set the currently logged in user for the application.
     *
     * @param \Illuminate\Contracts\Auth\Authenticatable|null $user
     *
     * @return \Tests\TestCase
     */
    public function signIn(?User $user = null): TestCase
    {
        $user = $user ?: create(User::class);

        if (static::$afterCreatingUser) {
            call_user_func(static::$afterCreatingUser, $user);
        }

        return $this->actingAs($user);
    }
}