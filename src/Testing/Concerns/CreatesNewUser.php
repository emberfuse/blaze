<?php

namespace Cratespace\Preflight\Testing\Concerns;

use App\Models\User;
use PHPUnit\Framework\TestCase;

trait CreatesNewUser
{
    /**
     * Create and set the currently logged in user for the application.
     *
     * @param mixed|null $user
     *
     * @return \PHPUnit\Framework\TestCase
     */
    public function signIn($user = null, array $overrides = []): TestCase
    {
        $user = $user ?: create(User::class, $overrides);

        return $this->actingAs($user);
    }
}
