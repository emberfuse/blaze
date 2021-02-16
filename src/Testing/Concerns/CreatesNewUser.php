<?php

namespace Cratespace\Preflight\Testing\Concerns;

use Closure;
use App\Models\User;
use PHPUnit\Framework\TestCase;
use Illuminate\Contracts\Auth\Authenticatable;

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
     * @return \PHPUnit\Framework\TestCase
     */
    public function signIn(?Authenticatable $user = null, array $overrides = []): TestCase
    {
        $user = $user ?: create(User::class, $overrides);

        if (static::$afterCreatingUser) {
            call_user_func(static::$afterCreatingUser, $user);
        }

        return $this->actingAs($user);
    }

    /**
     * Set actions to run after creating fake user.
     *
     * @param \Closure $callback
     *
     * @return void
     */
    public static function afterCreatingUser(Closure $callback): void
    {
        static::$afterCreatingUser = $callback;
    }
}
