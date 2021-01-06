<?php

namespace Tests\Unit\Auth;

use Mockery as m;
use Tests\TestCase;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Failed;
use App\Auth\Guards\LoginRateLimiter;
use Illuminate\Support\Facades\Event;
use App\Auth\Authenticators\Authenticator;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Validation\ValidationException;

class AuthenticatorTest extends TestCase
{
    public function testAuthenticatorCanBeInstantiated()
    {
        $authenticator = new ConcreteAuthenticator(
            ...$this->mockAuthenticatorDependencies()
        );

        $this->assertInstanceOf(Authenticator::class, $authenticator);
    }

    public function testTriggerAuthenticationFailedEvent()
    {
        Event::fake();

        $authenticator = new ConcreteAuthenticator(
            ...$this->mockAuthenticatorDependencies()
        );

        $authenticator->mockFireFailedEvent(Request::createFromGlobals());

        Event::assertDispatched(Failed::class);
    }

    public function testThrowFailedAuthenticationException()
    {
        $this->expectException(ValidationException::class);

        $mockRequest = Request::createFromGlobals();
        $loginRateLimiter = m::mock(LoginRateLimiter::class);
        $loginRateLimiter->shouldReceive('increment')
            ->once()
            ->with($mockRequest)
            ->andReturn(null);

        $authenticator = new ConcreteAuthenticator(
            m::mock(StatefulGuard::class),
            $loginRateLimiter
        );

        $authenticator->mockThrowFailedAuthenticationException($mockRequest);

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

class ConcreteAuthenticator extends Authenticator
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
