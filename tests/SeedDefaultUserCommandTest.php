<?php

namespace Cratespace\Preflight\Tests;

use Illuminate\Support\Facades\Hash;
use Cratespace\Preflight\Tests\Fixtures\User;

class SeedDefaultUserCommandTest extends TestCase
{
    public function testSeedDefaultUser()
    {
        $this->migrate();

        config()->set('auth.providers.users.model', User::class);
        config()->set('defaults.users.credentials', [
            'name' => 'James Silverman',
            'email' => 'silver.james@gmail.com',
            'password' => Hash::make('cthuluEmployee'),
        ]);

        $this->artisan('preflight:user')
            ->expectsConfirmation('Do you want to create a default user from preset data?', 'yes')
            ->assertExitCode(0);

        $this->assertTrue(User::whereName('James Silverman')->exists());
    }

    public function testSeedCustomUserDetails()
    {
        $this->migrate();

        $this->artisan('preflight:user')
            ->expectsConfirmation('Do you want to create a default user from preset data?', 'no')
            ->expectsQuestion('Full name', 'James Silverman')
            ->expectsQuestion('Email address', 'silver.james@monster.com')
            ->expectsQuestion('Password', 'cthuluEmployee')
            ->assertExitCode(0);

        $this->assertTrue(User::whereName('James Silverman')->exists());
    }
}
