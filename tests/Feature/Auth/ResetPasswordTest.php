<?php

namespace Tests\Feature\Auth;

use Mockery as m;
use Tests\TestCase;
use App\Models\User;
use Tests\Contracts\Postable;
use Illuminate\Support\Facades\Password;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Contracts\Auth\PasswordBroker;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ResetPasswordTest extends TestCase implements Postable
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

        $response = $this->withoutExceptionHandling()->post(
            '/reset-password',
            $this->validParameters()
        );

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
            ->andReturnUsing(fn ($input, $callback) => Password::INVALID_TOKEN);

        $response = $this->withoutExceptionHandling()->post(
            '/reset-password',
            $this->validParameters()
        );

        $response->assertStatus(303);
        $response->assertSessionHasErrors('email');
    }

    public function testPasswordResetCanFailWithXhr()
    {
        Password::shouldReceive('broker')->andReturn(
            $broker = m::mock(PasswordBroker::class)
        );
        $broker->shouldReceive('reset')
            ->andReturnUsing(fn ($input, $callback) => Password::INVALID_TOKEN);

        $response = $this->postJson('/reset-password', $this->validParameters());

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
            ->post('/reset-password', $this->validParameters([
                'emailAddress' => 'lord@tumbledor.com',
            ]));

        $response->assertStatus(303);
        $response->assertRedirect('/login');
    }

    /**
     * Provide only the necessary paramertes for a POST-able type request.
     *
     * @param array $overrides
     *
     * @return array
     */
    public function validParameters(array $overrides = []): array
    {
        $password = uniqid();

        return array_merge([
            'token' => uniqid(),
            'email' => $this->faker->email,
            'password' => $password,
            'password_confirmation' => $password,
        ], $overrides);
    }
}
