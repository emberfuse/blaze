<?php

namespace App\Providers;

use PragmaRX\Google2FA\Google2FA;
use Illuminate\Support\ServiceProvider;

class TwoFactorAuthenticationServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Google2FA::class, fn () => new Google2FA());
    }
}
