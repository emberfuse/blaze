<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Cratespace\Preflight\API\Permission;

class PreflightServiceProvider extends ServiceProvider
{
    use HasActions;

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->registerActions();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
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
