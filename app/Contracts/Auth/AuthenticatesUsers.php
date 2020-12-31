<?php

namespace App\Contracts\Auth;

use Illuminate\Http\Request;
use Illuminate\Contracts\Pipeline\Pipeline;

interface AuthenticatesUsers
{
    /**
     * Authenticate user into application.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return mixed
     */
    public function authenticate(Pipeline $pipeline, Request $request);
}
