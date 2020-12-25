<?php

namespace App\Auth\Middleware;

use Closure;
use Illuminate\Http\Request;

class RedirectIfLocked extends Authenticator
{
    /**
     * Handle the incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (optional($user = $this->validateCredentials($request))->isLocked()) {
            $this->fireFailedEvent($request, $user);

            abort(401, trans('auth.locked'));
        }

        return $next($request);
    }
}
