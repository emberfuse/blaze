<?php

namespace App\Auth\Actions;

use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;
use App\Contracts\AuthenticatesUsers;
use App\Providers\AuthServiceProvider;

class AuthenticateUser implements AuthenticatesUsers
{
    /**
     * Authenticate given login request.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return mixed
     */
    public function authenticate(Request $request)
    {
        return $this->loginPipeline($request);
    }

    /**
     * Perform neccessary procedures for log in attempt using provided request data.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return mixed
     */
    protected function loginPipeline(Request $request)
    {
        return (new Pipeline(app()))->send($request)
            ->through(array_filter(AuthServiceProvider::$authenticationMiddleware));
    }
}
