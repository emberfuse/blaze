<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Cratespace\Preflight\API\Permission;
use Cratespace\Sentinel\Providers\Traits\HasActions;

class PreflightServiceProvider extends ServiceProvider
{
    use HasActions;

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->registerActions();

        $this->configurePermissions();
    }

    /**
     * Configure the permissions that are available within the application.
     *
     * @return void
     */
    protected function configurePermissions()
    {
        Permission::defaultApiTokenPermissions(['read']);

        Permission::permissions([
            'create',
            'read',
            'update',
            'delete',
        ]);
    }
}
