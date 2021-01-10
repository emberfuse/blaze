<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Contracts\Auth\ConfirmsPasswords;
use Illuminate\Contracts\Auth\StatefulGuard;
use App\Http\Requests\ConfirmPasswordRequest;
use App\Http\Responses\PasswordConfirmedResponse;
use App\Http\Responses\FailedPasswordConfirmationResponse;

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
     * Confirm the user's password.
     *
     * @param \App\Http\Requests\ConfirmPasswordRequest $request
     *
     * @return \Illuminate\Contracts\Support\Responsable
     */
    public function __invoke(ConfirmPasswordRequest $request, ConfirmsPasswords $confirmable)
    {
        $confirmed = $confirmable->confirm(
            $this->guard,
            $request->user(),
            $request->password
        );

        if ($confirmed) {
            $request->session()->put('auth.password_confirmed_at', time());
        }

        return $confirmed
            ? $this->app(PasswordConfirmedResponse::class)
            : $this->app(FailedPasswordConfirmationResponse::class);
    }
}
