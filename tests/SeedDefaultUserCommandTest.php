<?php

namespace Emberfuse\Blaze\Tests;

use Illuminate\Support\Facades\Hash;
use Emberfuse\Blaze\Tests\Fixtures\User;

class SeedDefaultUserCommandTest extends TestCase
{
    public function testSeedDefaultUser()
    {
        $this->migrate();

        config()->set('auth.providers.users.model', User::class);
        config()->set('defaults.users.credentials', [
            'name' => 'James Silverman',
            'username' => 'JKAMonster',
            'email' => 'silver.james@gmail.com',
            'password' => Hash::make('cthuluEmployee'),
        ]);

        $this->artisan('blaze:user')
            ->expectsConfirmation('Do you want to create a default user from preset data?', 'yes')
            ->assertExitCode(0);

        $this->assertTrue(User::whereName('James Silverman')->exists());
    }

    public function testSeedCustomUserDetails()
    {
        $this->withoutExceptionHandling();

        $this->migrate();

        $this->artisan('blaze:user')
            ->expectsConfirmation('Do you want to create a default user from preset data?', 'no')
            ->expectsQuestion('Full name', 'James Silverman')
            ->expectsQuestion('Username', 'JamesSilverman')
            ->expectsQuestion('Email address', 'silver.james@monster.com')
            ->expectsQuestion('Phone number', '0112345678')
            ->expectsQuestion('Password', 'cthuluEmployee')
            ->assertExitCode(0);

        $this->assertTrue(User::whereName('James Silverman')->exists());
    }
}
