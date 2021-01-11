<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Responses\Response;
use Illuminate\Pipeline\Pipeline;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use App\Http\Responses\LoginResponse;
use App\Http\Responses\LogoutResponse;
use Inertia\Response as InertiaResponse;
use App\Contracts\Auth\AuthenticatesUsers;
use Illuminate\Contracts\Auth\StatefulGuard;

class AuthenticationController extends Controller
{
    /**
     * The guard implementation.
     *
     * @var \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected $guard;

    /**
     * Create a new controller instance.
     *
     * @param  \Illuminate\Contracts\Auth\StatefulGuard
     *
     * @return void
     */
    public function __construct(StatefulGuard $guard)
    {
        $this->guard = $guard;
    }

    /**
     * Show the login view.
     *
     * @return \Inertia\InertiaResponse
     */
    public function create(): InertiaResponse
    {
        return inertia('Auth/Login');
    }

    /**
     * Attempt to authenticate a new session.
     *
     * @param \App\Http\Requests\LoginRequest        $request
     * @param \App\Contracts\Auth\AuthenticatesUsers $authenticator
     *
     * @return \App\Http\Responses\Response
     */
    public function store(LoginRequest $request, AuthenticatesUsers $authenticator): Response
    {
        return $authenticator->authenticate(new Pipeline($this->app()), $request)
            ->then(fn (): Response => $this->app(LoginResponse::class));
    }

    /**
     * Destroy an authenticated session.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \App\Http\Responses\Response
     */
    public function destroy(Request $request): Response
    {
        $this->guard->logout();

        tap($request->session(), function ($session) {
            $session->invalidate();

            $session->regenerateToken();
        });

        return app(LogoutResponse::class);
    }
}
