<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use App\Events\RecoveryCodesGenerated;
use Illuminate\Auth\Events\Registered;
use App\Events\TwoFactorAuthenticationEnabled;
use App\Events\TwoFactorAuthenticationDisabled;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        RecoveryCodesGenerated::class => [],

        TwoFactorAuthenticationEnabled::class => [],

        TwoFactorAuthenticationDisabled::class => [],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
    }
}
