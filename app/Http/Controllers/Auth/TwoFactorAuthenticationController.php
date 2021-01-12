<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Auth\Actions\EnableTwoFactorAuthentication;
use App\Auth\Actions\DisableTwoFactorAuthentication;
use App\Http\Responses\Auth\EnableTwoFactorAuthenticationResponse;
use App\Http\Responses\Auth\DisableTwoFactorAuthenticationResponse;

class TwoFactorAuthenticationController extends Controller
{
    /**
     * Enable two factor authentication for the user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Auth\Actions\EnableTwoFactorAuthentication  $enable
     * @return \App\Http\Responses\EnableTwoFactorAuthenticationResponse
     */
    public function store(Request $request, EnableTwoFactorAuthentication $enable): EnableTwoFactorAuthenticationResponse
    {
        $enable($request->user());

        return $this->app(EnableTwoFactorAuthenticationResponse::class);
    }

    /**
     * Disable two factor authentication for the user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Auth\Actions\DisableTwoFactorAuthentication  $disable
     * @return \App\Http\Responses\DisableTwoFactorAuthenticationResponse
     */
    public function destroy(Request $request, DisableTwoFactorAuthentication $disable): DisableTwoFactorAuthenticationResponse
    {
        $disable($request->user());

        return $this->app(DisableTwoFactorAuthenticationResponse::class);
    }
}
