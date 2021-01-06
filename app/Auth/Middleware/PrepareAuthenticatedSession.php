<?php

namespace App\Auth\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Auth\Authenticators\Authenticator;

class PrepareAuthenticatedSession extends Authenticator
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
        $request->session()->regenerate();

        $this->limiter->clear($request);

        return $next($request);
    }
}
