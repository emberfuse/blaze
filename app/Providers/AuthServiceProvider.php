<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use App\Auth\Actions\AuthenticateUser;
use App\Contracts\Auth\AuthenticatesUsers;
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
     * Complete list of all classes neccessary to perform authenticated user actions.
     *
     * @var array
     */
    public static $authActions = [
        AuthenticatesUsers::class => AuthenticateUser::class,
    ];

    /**
     * Login middleware pipeline.
     *
     * @var array
     */
    public static $loginPipeline = [
        EnsureLoginIsNotThrottled::class,
        RedirectIfTwoFactorAuthenticatable::class,
        AttemptToAuthenticate::class,
        PrepareAuthenticatedSession::class,
    ];

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
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerAuthGuard();
        $this->registerAuthActions();
    }

    /**
     * Register all auth action classes.
     *
     * @return void
     */
    protected function registerAuthActions(): void
    {
        foreach (static::$authActions as $abstract => $concrete) {
            $this->app->singleton($abstract, $concrete);
        }
    }

    /**
     * Register appropriate guard used for user authentication.
     *
     * @return void
     */
    protected function registerAuthGuard(): void
    {
        $this->app->bind(
            StatefulGuard::class,
            fn () => Auth::guard(config('auth.defaults.guard', 'web'))
        );
    }
}
