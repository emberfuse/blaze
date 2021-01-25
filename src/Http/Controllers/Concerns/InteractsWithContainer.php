<?php

namespace Cratespace\Preflight\Http\Controllers\Concerns;

trait InteractsWithContainer
{
    /**
     * Get the available container instance.
     *
     * @param string|null $abstract
     * @param array       $parameters
     *
     * @return mixed|\Illuminate\Contracts\Foundation\Application
     */
    protected function app(?string $abstract = null, array $parameters = [])
    {
        return is_null($abstract) ? app() : app()->make($abstract, $parameters);
    }
}
