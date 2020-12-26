<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use App\Contracts\AuthenticatesUsers;
use App\Auth\Actions\AuthenticateUser;
use App\Auth\Middleware\RedirectIfLocked;
use Illuminate\Contracts\Auth\StatefulGuard;
use App\Auth\Middleware\AttemptToAuthenticate;
use App\Auth\Middleware\EnsureLoginIsNotThrottled;
use App\Auth\Middleware\PrepareAuthenticatedSession;
use App\Auth\Middleware\RedirectIfTwoFactorAuthenticatable;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Default user attribute to use as username.
     *
     * @var string
     */
    public const USERNAME = 'email';

    /**
     * Authenticated user action classes.
     *
     * @var array
     */
    public static array $authUserActions = [
        AuthenticatesUsers::class => AuthenticateUser::class,
    ];

    /*
     * Middleware classes used to authenticate a user into the app.
     *
     * @var array
     */
    public static $authenticationMiddleware = [
        // EnsureLoginIsNotThrottled::class,
        // RedirectIfLocked::class,
        // RedirectIfTwoFactorAuthenticatable::class,
        AttemptToAuthenticate::class,
        PrepareAuthenticatedSession::class,
    ];

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerAuthGuards();
        $this->registerAuthenticators();
    }

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
    }

    /**
     * Register authentication guard to be used for application authentication.
     *
     * @return void
     */
    protected function registerAuthGuards(): void
    {
        $this->app->bind(StatefulGuard::class, function () {
            return Auth::guard('web');
        });
    }

    /**
     * Register session authenticator.
     *
     * @return void
     */
    protected function registerAuthenticators(): void
    {
        foreach (static::$authUserActions as $abstract => $concrete) {
            $this->app->singleton($abstract, $concrete);
        }
    }
}
