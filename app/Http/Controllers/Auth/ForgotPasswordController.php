<?php

namespace App\Http\Controllers\Auth;

use App\Http\Responses\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Password;
use Inertia\Response as InertiaResponse;
use Illuminate\Contracts\Auth\PasswordBroker;
use App\Http\Requests\Auth\ResetPasswordLinkRequest;
use App\Http\Responses\Auth\FailedPasswordResetLinkRequestResponse;
use App\Http\Responses\Auth\SuccessfulPasswordResetLinkRequestResponse;

class ForgotPasswordController extends Controller
{
    /**
     * The broker to be used during password reset.
     *
     * @var \Illuminate\Contracts\Auth\PasswordBroker
     */
    protected $broker;

    /**
     * Create new instance of controller.
     *
     * @param \Illuminate\Contracts\Auth\PasswordBroker $broker
     */
    public function __construct(PasswordBroker $broker)
    {
        $this->broker = $broker;
    }

    /**
     * Show password reset link request page.
     *
     * @return \Inertia\InertiaResponse
     */
    public function create(): InertiaResponse
    {
        return inertia('Auth/ForgotPassword');
    }

    /**
     * Send a reset link to the given user.
     *
     * @param \App\Http\Requests\ResetPasswordLinkRequest $request
     *
     * @return \App\Http\Responses\Response
     */
    public function store(ResetPasswordLinkRequest $request): Response
    {
        $status = $this->broker->sendResetLink($request->only('email'));

        return $status == Password::RESET_LINK_SENT
            ? $this->app(SuccessfulPasswordResetLinkRequestResponse::class, compact('status'))
            : $this->app(FailedPasswordResetLinkRequestResponse::class, compact('status'));
    }
}
