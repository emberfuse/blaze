<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class PreflightServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
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
