<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use App\Contracts\AuthenticatesUsers;
use Illuminate\Contracts\Auth\StatefulGuard;
use Symfony\Component\HttpFoundation\Response;

class AuthenticatedSessionsController extends Controller
{
    /**
     * User session authenticator.
     *
     * @var \App\Contracts\Auth\AuthenticatesUsers
     */
    protected $authenticator;

    /**
     * The guard implementation.
     *
     * @var \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected $guard;

    /**
     * Create new controller instance.
     *
     * @param \App\Contracts\Auth\AuthenticatesUsers   $authenticator
     * @param \Illuminate\Contracts\Auth\StatefulGuard $guard
     *
     * @return void
     */
    public function __construct(AuthenticatesUsers $authenticator, StatefulGuard $guard)
    {
        $this->authenticator = $authenticator;
        $this->guard = $guard;
    }

    /**
     * Attempt to authenticate a new session.
     *
     * @param \App\Http\Requests\LoginRequest $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function store(LoginRequest $request): Response
    {
        return $this->authenticator->authenticate($request)
            ->then(function (Request $request): Response {
                $token = $request->user()
                    ->createToken($request->userAgent())
                    ->plainTextToken;

                return $request->wantsJson()
                    ? response()->json(['tfa' => false, 'token' => $token])
                    : redirect()->intended(config('auth.defaults.home'));
            });
    }

    /**
     * Destroy an authenticated session.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function destroy(Request $request): Response
    {
        $request->user()->tokens()->whereName($request->userAgent())->delete();

        $this->guard->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return $request->wantsJson()
            ? response()->json('', 204)
            : redirect('/');
    }
}
