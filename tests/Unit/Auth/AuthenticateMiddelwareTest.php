<?php

namespace Tests\Unit\Auth;

use Mockery as m;
use Tests\TestCase;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Failed;
use App\Auth\Middleware\Authenticate;
use Illuminate\Support\Facades\Event;
use App\Auth\Limiters\LoginRateLimiter;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Validation\ValidationException;

class AuthenticateMiddelwareTest extends TestCase
{
    public function test_authenticator_can_be_instantiated()
    {
        $authenticatorMiddleware = new ConcreteAuthenticatorMiddleware(
            ...$this->mockAuthenticatorDependencies()
        );

        $this->assertInstanceOf(Authenticate::class, $authenticatorMiddleware);
    }

    public function test_trigger_authentication_failed_event()
    {
        Event::fake();

        $authenticatorMiddleware = new ConcreteAuthenticatorMiddleware(
            ...$this->mockAuthenticatorDependencies()
        );

        $authenticatorMiddleware->mockFireFailedEvent(Request::createFromGlobals());

        Event::assertDispatched(Failed::class);
    }

    public function test_throw_failed_authentication_exception()
    {
        $this->expectException(ValidationException::class);

        $mockRequest = Request::createFromGlobals();
        $loginRateLimiter = m::mock(LoginRateLimiter::class);
        $loginRateLimiter->shouldReceive('increment')
            ->once()
            ->with($mockRequest)
            ->andReturn(null);

        $authenticatorMiddleware = new ConcreteAuthenticatorMiddleware(
            m::mock(StatefulGuard::class),
            $loginRateLimiter
        );

        $authenticatorMiddleware->mockThrowFailedAuthenticationException($mockRequest);

        Event::assertDispatched(Failed::class);
    }

    /**
     * Mock all authenticator class dependencies.
     *
     * @return array
     */
    protected function mockAuthenticatorDependencies(): array
    {
        return [
            m::mock(StatefulGuard::class),
            m::mock(LoginRateLimiter::class),
        ];
    }
}

class ConcreteAuthenticatorMiddleware extends Authenticate
{
    /**
     * Mock firing the failed login event.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return void
     */
    public function mockFireFailedEvent(Request $request): void
    {
        $this->fireFailedEvent($request);
    }

    /**
     * Mock throwing failed authentication validation exception.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return void
     */
    public function mockThrowFailedAuthenticationException(Request $request): void
    {
        $this->throwFailedAuthenticationException($request);
    }
}
