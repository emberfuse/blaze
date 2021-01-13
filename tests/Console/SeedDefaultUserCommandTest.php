<?php

namespace Tests\Console;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SeedDefaultUserCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_creates_a_default_user_from_preset_set_credentials()
    {
        config()->set(
            'defaults.users.credentials',
            $user = make(User::class)->toArray()
        );

        $this->artisan('user:create')
            ->expectsConfirmation('Do you want to create a default user from preset data?', 'yes')
            ->assertExitCode(0);

        $this->assertDatabaseHas('users', ['email' => $user['email']]);
    }

    public function test_creates_a_user_from_user_input_credentials()
    {
        $this->artisan('user:create')
            ->expectsConfirmation('Do you want to create a default user from preset data?', 'no')
            ->expectsQuestion('Full name', 'Thavarshan Thayananthajothy')
            ->expectsQuestion('Email address', 'tjthavarshan@gmail.com')
            ->expectsQuestion('Password', 'ComeGetMe!')
            ->assertExitCode(0);

        $this->assertDatabaseHas('users', ['email' => 'tjthavarshan@gmail.com']);
    }
}
