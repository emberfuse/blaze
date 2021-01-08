<?php

namespace App\Auth\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Authenticatable;

class AttemptToAuthenticate extends Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $authenticated = $this->guard->attempt(
            $request->only($this->username(), 'password'),
            $request->filled('remember')
        );

        if ($authenticated) {
            $this->authenticated($request, $this->guard->user());

            return $next($request);
        }

        $this->throwFailedAuthenticationException($request);
    }

    /**
     * Actions to be performed after the user has been authenticated.
     *
     * @param \Illuminate\Http\Request                   $request
     * @param \Illuminate\Contracts\Auth\Authenticatable $user
     *
     * @return mixed
     */
    protected function authenticated(Request $request, Authenticatable $user)
    {
        if (! is_null(static::$authenticated)) {
            return call_user_func_array(static::$authenticated, [$request, $user]);
        }
    }
}
