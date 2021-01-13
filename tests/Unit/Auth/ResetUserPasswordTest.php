<?php

namespace Tests\Unit\Auth;

use Mockery as m;
use Tests\TestCase;
// use PHPUnit\Framework\TestCase;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use App\Auth\Actions\ResetUserPassword;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use App\Contracts\Auth\ResetsUserPasswords;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Contracts\Auth\PasswordBroker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ResetUserPasswordTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        m::close();
    }

    public function test_it_can_be_instantiated()
    {
        $resetor = new ResetUserPassword(
            m::mock(PasswordBroker::class),
            m::mock(StatefulGuard::class),
        );

        $this->assertInstanceOf(ResetsUserPasswords::class, $resetor);
    }

    public function test_it_can_reset_given_users_password()
    {
        Event::fake();

        $user = create(User::class);
        $mockRequest = Request::create('/', 'POST', ['password' => 'myNewPassword']);
        $broker = m::mock(PasswordBroker::class);
        $guard = m::mock(StatefulGuard::class);
        $broker->shouldReceive('reset')
            ->andReturnUsing(function ($input, $callback) use ($user) {
                $callback($user, 'password');

                return Password::PASSWORD_RESET;
            });

        $resetor = new ResetUserPassword($broker, $guard);

        $this->assertEquals($resetor->reset($mockRequest), Password::PASSWORD_RESET);

        Event::assertDispatched(PasswordReset::class);
    }
}
