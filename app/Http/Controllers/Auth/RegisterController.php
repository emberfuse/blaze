<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Inertia\Response as InertiaResponse;

class RegisterController extends Controller
{
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
     * @param \App\Http\Requests\LoginRequest        $request
     * @param \App\Contracts\Auth\AuthenticatesUsers $authenticator
     *
     * @return \App\Http\Responses\Response
     */
    public function store(): Response
    {
    }
}
