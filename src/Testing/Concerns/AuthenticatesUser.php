<?php

namespace Cratespace\Preflight\Testing\Concerns;

use App\Models\User;

trait AuthenticatesUser
{
    /**
     * Create and set the currently logged in user for the application.
     *
     * @param mixed|null  $user
     * @param array[]     $overrides
     * @param string|null $as
     *
     * @return mixed
     */
    public function signIn($user = null, array $overrides = [], ?string $as = null)
    {
        $user = $user ?: create(User::class, $overrides, $as);

        return $this->actingAs($user);
    }
}
