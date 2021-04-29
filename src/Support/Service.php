<?php

namespace Cratespace\Preflight\Support;

use Illuminate\Contracts\Foundation\Application;

abstract class Service
{
    /**
     * Instance of Cratesapce application.
     *
     * @var \Illuminate\Contracts\Foundation\Application
     */
    protected $app;

    /**
     * Create new instance of the service class.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     *
     * @return void
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }
}
