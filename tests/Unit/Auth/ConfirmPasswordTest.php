<?php

namespace Tests\Unit\Auth;

use Mockery as m;
use Tests\TestCase;
use App\Auth\Actions\ConfirmPassword;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Contracts\Auth\Authenticatable;

class ConfirmPasswordTest extends TestCase
{
    protected function tearDown(): void
    {
        m::close();
    }

    public function test_can_confirm_authenticatable_user_password_mock()
    {
        $user = m::mock(Authenticatable::class);
        $user->email = 'test.example@example.com';
        $guard = m::mock(StatefulGuard::class);
        $guard->shouldReceive('validate')
            ->with([
                'email' => 'test.example@example.com',
                'password' => 'MyTopSecretPassword',
            ])
            ->once()
            ->andReturn(true);

        $confirmer = new ConfirmPassword();

        $this->assertTrue(
            $confirmer->confirm($guard, $user, 'MyTopSecretPassword')
        );
    }
}
