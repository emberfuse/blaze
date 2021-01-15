<?php

namespace App\Providers;

use App\Models\Permission;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class APIServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
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
    protected function configurePermissions(): void
    {
        if (! Schema::hasTable('permissions')) {
            return;
        }

        Permission::defaultApiTokenPermissions(['read']);

        Permission::permissions([
            'create',
            'read',
            'update',
            'delete',
        ]);
    }
}
