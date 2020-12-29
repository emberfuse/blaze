<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use App\Contracts\AuthenticatesUsers;
use App\Http\Responses\LoginResponse;
use Illuminate\Contracts\Auth\StatefulGuard;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Contracts\Foundation\Application;

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
     * @param \Illuminate\Contracts\Foundation\Application $app
     * @param \App\Contracts\Auth\AuthenticatesUsers       $authenticator
     * @param \Illuminate\Contracts\Auth\StatefulGuard     $guard
     *
     * @return void
     */
    public function __construct(Application $app, AuthenticatesUsers $authenticator, StatefulGuard $guard)
    {
        parent::__construct($app);

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
                return $this->app->make(LoginResponse::class, [$request]);
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
