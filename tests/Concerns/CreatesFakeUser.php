<?php

namespace Tests\Concerns;

use App\Models\User;
use App\Models\Account;
use App\Models\Business;

trait CreatesFakeUser
{
    /**
     * Instance of fake user.
     *
     * @var \App\Models\User
     */
    protected $user;

    /**
     * Mock authenticated user.
     *
     * @param \App\Models\User $user
     *
     * @return \App\Models\User
     */
    protected function signIn(?User $user = null): User
    {
        $user ?? create(User::class);

        $this->createBusiness($user);
        $this->createCreditAccount($user);
        $this->actingAs($user);

        return $user;
    }

    /**
     * Create a fake business profile for the user.
     *
     * @param \App\Models\User
     *
     * @return void
     */
    protected function createBusiness(User $user): void
    {
        // create(Business::class, ['user_id' => $user->id]);
    }

    /**
     * Create a fake financial account for the user.
     *
     * @param \App\Models\User
     *
     * @return void
     */
    protected function createCreditAccount(User $user): void
    {
        // create(Account::class, ['user_id' => $this->user->id]);
    }
}
