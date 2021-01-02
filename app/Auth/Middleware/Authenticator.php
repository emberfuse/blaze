<?php

namespace App\Auth\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Failed;
use App\Auth\Guards\LoginRateLimiter;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Validation\ValidationException;

abstract class Authenticator
{
    /**
     * The guard implementation.
     *
     * @var \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected $guard;

    /**
     * The login rate limiter instance.
     *
     * @var \Laravel\Fortify\LoginRateLimiter
     */
    protected $limiter;

    /**
     * Callback that will be executed after a user has been authenticated.
     *
     * @var \Closure|null
     */
    protected static $authenticated;

    /**
     * Create a new controller instance.
     *
     * @param \Illuminate\Contracts\Auth\StatefulGuard $guard
     * @param \App\Auth\Guards\LoginRateLimiter        $limiter
     *
     * @return void
     */
    public function __construct(StatefulGuard $guard, LoginRateLimiter $limiter)
    {
        $this->guard = $guard;
        $this->limiter = $limiter;
    }

    /**
     * Register a callback that will be executed after a user has been authenticated.
     *
     * @param \Closure|null $callback
     *
     * @return void
     */
    public static function afterAuthentication(?Closure $callback = null): void
    {
        static::$authenticated = $callback;
    }

    /**
     * Throw a failed authentication validation exception.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function throwFailedAuthenticationException(Request $request): void
    {
        $this->limiter->increment($request);

        throw ValidationException::withMessages(
            [$this->username() => [trans('auth.failed')]]
        );
    }

    /**
     * Fire the failed authentication attempt event with the given arguments.
     *
     * @param \Illuminate\Http\Request                        $request
     * @param \Illuminate\Contracts\Auth\Authenticatable|null $user
     *
     * @return void
     */
    protected function fireFailedEvent(Request $request, ?Authenticatable $user = null): void
    {
        event(new Failed(config('auth.defaults.guard'), $user, [
            $this->username() => $request->{$this->username()},
            'password' => $request->password,
        ]));
    }

    /**
     * Get the username used for authentication.
     *
     * @return string
     */
    public function username(): string
    {
        return config('auth.credentials.username', 'email');
    }
}
