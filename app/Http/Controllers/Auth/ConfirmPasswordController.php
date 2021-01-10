<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Contracts\Auth\ConfirmsPasswords;
use Illuminate\Contracts\Auth\StatefulGuard;

class ConfirmPasswordController extends Controller
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
     * @param \Illuminate\Contracts\Auth\StatefulGuard $guard
     *
     * @return void
     */
    public function __construct(StatefulGuard $guard)
    {
        $this->guard = $guard;
    }

    /**
     * Show the confirm password view.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Laravel\Fortify\Contracts\ConfirmPasswordViewResponse
     */
    public function show(Request $request)
    {
        return app(ConfirmPasswordViewResponse::class);
    }

    /**
     * Confirm the user's password.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\Support\Responsable
     */
    public function store(Request $request, ConfirmsPasswords $confirmable)
    {
        $confirmed = $confirmable->confirm(
            $this->guard,
            $request->user(),
            $request->input('password')
        );

        if ($confirmed) {
            $request->session()->put('auth.password_confirmed_at', time());
        }

        return $confirmed
            ? $this->app(PasswordConfirmedResponse::class)
            : $this->app(FailedPasswordConfirmationResponse::class);
    }
}
