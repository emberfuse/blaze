<?php

namespace Cratespace\Preflight\Providers\Traits;

trait HasActions
{
    /**
     * Register all action classes in the given array.
     *
     * @return void
     */
    public function registerActions(): void
    {
        if (! property_exists($this, 'actions')) {
            return;
        }

        collect($this->actions)->each(
            fn ($concrete, $abstract) => $this->app->singleton($abstract, $concrete)
        );
    }
}
