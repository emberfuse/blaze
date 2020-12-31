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

class LoginController extends Controller
{
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
            ->then(fn (Request $request): Response => $this->app(LoginResponse::class));
    }

    /**
     * Destroy an authenticated session.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \App\Http\Responses\LogoutResponse
     */
    public function destroy(Request $request): LogoutResponse
    {
        $this->guard->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return app(LogoutResponse::class);
    }
}
