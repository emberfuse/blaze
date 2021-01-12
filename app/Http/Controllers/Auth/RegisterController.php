<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use Illuminate\Auth\Events\Registered;
use App\Contracts\Auth\CreatesNewUsers;
use Inertia\Response as InertiaResponse;
use Illuminate\Contracts\Auth\StatefulGuard;
use App\Http\Responses\Auth\RegisterResponse;

class RegisterController extends Controller
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
        return inertia('Auth/Register');
    }

    /**
     * Attempt to authenticate a new session.
     *
     * @param \App\Http\Requests\RegisterRequest  $request
     * @param \App\Contracts\Auth\CreatesNewUsers $authenticator
     *
     * @return \App\Http\Responses\RegisterResponse
     */
    public function store(RegisterRequest $request, CreatesNewUsers $creator): RegisterResponse
    {
        event(new Registered($user = $creator->create($request->validated())));

        $this->guard->login($user);

        return $this->app(RegisterResponse::class);
    }
}
