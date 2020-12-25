<?php

namespace App\Auth\Middleware;

use Illuminate\Http\Request;
use App\Guards\LoginRateLimiter;
use Illuminate\Auth\Events\Failed;
use Illuminate\Support\Facades\Hash;
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
     * @var \App\Guards\LoginRateLimiter
     */
    protected $limiter;

    /**
     * Create a new controller instance.
     *
     * @param \Illuminate\Contracts\Auth\StatefulGuard $guard
     * @param \App\Guards\LoginRateLimiter             $limiter
     *
     * @return void
     */
    public function __construct(StatefulGuard $guard, LoginRateLimiter $limiter)
    {
        $this->guard = $guard;
        $this->limiter = $limiter;
    }

    /**
     * Attempt to validate the incoming credentials.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    protected function validateCredentials(Request $request): ?Authenticatable
    {
        return tap($this->findUser($request), function ($user = null) use ($request) {
            if (is_null($user) || !Hash::check($request->password, $user->password)) {
                $this->fireFailedEvent($request, $user);

                $this->throwFailedAuthenticationException($request);
            }
        });
    }

    /**
     * Find user details of user trying to authenticate.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    protected function findUser(Request $request): ?Authenticatable
    {
        return app()->make($this->guard->getProvider()->getModel())
            ->where($this->username(), $request->{$this->username()})
            ->first();
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

        throw ValidationException::withMessages([$this->username() => [trans('auth.failed')]]);
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
        event(new Failed('web', $user, [
            $this->username() => $request->{$this->username()},
            'password' => $request->password,
        ]));
    }

    /**
     * Get default user user anme attribute.
     *
     * @return string
     */
    protected function username(): string
    {
        return config('auth.defaults.username');
    }
}
