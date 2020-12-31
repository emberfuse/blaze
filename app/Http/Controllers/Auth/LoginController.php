<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Responses\Response;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use App\Http\Responses\LoginResponse;
use App\Contracts\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
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
        return $authenticator->authenticate($request)->then(
            fn (Request $request): Response => $this->app(LoginResponse::class)
        );
    }
}
