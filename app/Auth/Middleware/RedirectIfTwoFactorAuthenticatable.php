<?php

namespace App\Auth\Middleware;

use Closure;
use Illuminate\Http\Request;

class RedirectIfTwoFactorAuthenticatable extends Authenticator
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
        return $next($request);
    }
}
