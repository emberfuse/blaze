<?php

namespace Tests\Feature\Auth;

use Mockery as m;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Password;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Contracts\Auth\PasswordBroker;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RequestPasswordTest extends TestCase
{
    use RefreshDatabase;

    public function testTheNewPasswordViewIsReturned()
    {
        $response = $this->get('/reset-password/token');

        $response->assertStatus(200);
    }

    public function testUserCanResetPassword()
    {
        $user = create(User::class, ['email' => 'lord@tumbledor.com']);

        Password::shouldReceive('broker')->andReturn(
            $broker = m::mock(PasswordBroker::class)
        );
        $broker->shouldReceive('reset')
            ->andReturnUsing(function ($input, $callback) use ($user) {
                $callback($user, 'password');

                return Password::PASSWORD_RESET;
            });

        $response = $this->withoutExceptionHandling()
            ->post('/reset-password', [
                'token' => 'token',
                'email' => 'lord@tumbledor.com',
                'password' => 'password',
                'password_confirmation' => 'password',
            ]);

        $response->assertStatus(303);
        $response->assertRedirect('/login');
    }

    public function testPasswordResetCanFail()
    {
        Password::shouldReceive('broker')->andReturn(
            $broker = m::mock(PasswordBroker::class)
        );

        $guard = $this->mock(StatefulGuard::class);
        $user = m::mock(Authenticatable::class);

        $broker->shouldReceive('reset')
            ->andReturnUsing(function ($input, $callback) {
                return Password::INVALID_TOKEN;
            });

        $response = $this->withoutExceptionHandling()
            ->post('/reset-password', [
                'token' => 'token',
                'email' => 'lord@tumbledor.com',
                'password' => 'password',
                'password_confirmation' => 'password',
            ]);

        $response->assertStatus(303);
        $response->assertSessionHasErrors('email');
    }

    public function testPasswordResetCanFailWithXhr()
    {
        Password::shouldReceive('broker')->andReturn(
            $broker = m::mock(PasswordBroker::class)
        );
        $broker->shouldReceive('reset')
            ->andReturnUsing(function ($input, $callback) {
                return Password::INVALID_TOKEN;
            });

        $response = $this->postJson('/reset-password', [
            'token' => 'token',
            'email' => 'lord@tumbledor.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('email');
    }

    public function testPasswordCanBeResetWithCustomizedEmailAddressField()
    {
        $user = create(User::class, ['email' => 'lord@tumbledor.com']);

        config()->set('auth.credentials.email', 'emailAddress');
        Password::shouldReceive('broker')->andReturn(
            $broker = m::mock(PasswordBroker::class)
        );

        $guard = $this->mock(StatefulGuard::class);
        $guard->shouldReceive('login')->never();

        $broker->shouldReceive('reset')
            ->andReturnUsing(function ($input, $callback) use ($user) {
                $callback($user, 'password');

                return Password::PASSWORD_RESET;
            });

        $response = $this->withoutExceptionHandling()
            ->post('/reset-password', [
                'token' => 'token',
                'emailAddress' => 'lord@tumbledor.com',
                'password' => 'password',
                'password_confirmation' => 'password',
            ]);

        $response->assertStatus(303);
        $response->assertRedirect('/login');
    }
}
