<?php

namespace App\Auth\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Auth\Authenticatable;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Traits\TwoFactorAuthenticatable;

class RedirectIfTwoFactorAuthenticatable extends Authenticator
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
        $user = $this->validateCredentials($request, function ($user, $request) {
            return is_null($user) || Hash::check($request->password, $user->password);
        });

        if (optional($user)->two_factor_enabled &&
            in_array(TwoFactorAuthenticatable::class, class_uses_recursive($user))) {
            return $this->twoFactorChallengeResponse($request, $user);
        }

        return $next($request);
    }

    /**
     * Get the two factor authentication enabled response.
     *
     * @param \Illuminate\Http\Request                   $request
     * @param \Illuminate\Contracts\Auth\Authenticatable $user
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function twoFactorChallengeResponse(Request $request, Authenticatable $user): Response
    {
        $request->session()->put([
            'signin.id' => $user->getKey(),
            'signin.remember' => $request->filled('remember'),
        ]);

        return $request->wantsJson()
            ? response()->json(['tfa' => true])
            : redirect()->route('tfa.signin');
    }
}
