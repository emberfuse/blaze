<?php

namespace App\Auth\Actions;

use Illuminate\Http\Request;
use App\Providers\AuthServiceProvider;
use App\Contracts\Auth\AuthenticatesUsers;
use Illuminate\Contracts\Pipeline\Pipeline;

class AuthenticateUser implements AuthenticatesUsers
{
    /**
     * Authenticate user into application.
     *
     * @param \Illuminate\Contracts\Pipeline\Pipeline $pipeline
     * @param \Illuminate\Http\Request                $request
     * @return mixed
     */
    public function authenticate(Pipeline $pipeline, Request $request)
    {
        return $pipeline->send($request)->through($this->getLoginPipeline());
    }

    /**
     * Get login middleware pipeline.
     *
     * @return array
     */
    protected function getLoginPipeline(): array
    {
        return array_filter(AuthServiceProvider::$loginPipeline);
    }
}
