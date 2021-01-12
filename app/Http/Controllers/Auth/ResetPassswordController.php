<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Responses\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Password;
use Inertia\Response as InertiaResponse;
use App\Contracts\Auth\ResetsUserPasswords;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Responses\Auth\PasswordResetResponse;
use App\Http\Responses\Auth\FailedPasswordResetResponse;

class ResetPassswordController extends Controller
{
    /**
     * Show the new password view.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Inertia\Response
     */
    public function create(Request $request): InertiaResponse
    {
        return inertia('Auth/ResetPassword', [
            'token' => $request->route('token'),
        ]);
    }

    /**
     * Reset the user's password.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \App\Http\Responses\Response
     */
    public function store(ResetPasswordRequest $request, ResetsUserPasswords $resetter): Response
    {
        $status = $resetter->reset($request);

        return $status == Password::PASSWORD_RESET
            ? $this->app(PasswordResetResponse::class, ['status' => $status])
            : $this->app(FailedPasswordResetResponse::class, ['status' => $status]);
    }
}
