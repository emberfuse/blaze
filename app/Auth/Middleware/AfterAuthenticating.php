<?php

namespace App\Auth\Middleware;

use Closure;
use Illuminate\Http\Request;

class AfterAuthenticating extends Authenticator
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
        if (static::$authenticated) {
            $response = call_user_func_array(
                static::$authenticated, [$request, $this->guard->user()]
            );

            return ! is_null($response) ? $response : $next($request);
        }

        return $next($request);
    }
}
