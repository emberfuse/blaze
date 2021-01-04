<?php

namespace App\Providers;

use App\Models\User;
use App\Policies\UserPolicy;
use App\Auth\Actions\DeleteUser;
use App\Auth\Actions\CreateNewUser;
use App\Contracts\Auth\DeletesUsers;
use Illuminate\Support\Facades\Auth;
use App\Auth\Actions\AuthenticateUser;
use App\Auth\Middleware\Authenticator;
use App\Auth\Actions\ResetUserPassword;
use App\Auth\Actions\UpdateUserProfile;
use App\Contracts\Auth\CreatesNewUsers;
use App\Auth\Actions\UpdateUserPassword;
use App\Contracts\Auth\AuthenticatesUsers;
use App\Contracts\Auth\ResetsUserPasswords;
use App\Contracts\Auth\UpdatesUserProfiles;
use App\Auth\Middleware\AfterAuthenticating;
use App\Contracts\Auth\UpdatesUserPasswords;
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
        User::class => UserPolicy::class,
    ];

    /**
     * Complete list of all classes neccessary to perform authenticated user actions.
     *
     * @var array
     */
    public static $authActions = [
        AuthenticatesUsers::class => AuthenticateUser::class,
        CreatesNewUsers::class => CreateNewUser::class,
        ResetsUserPasswords::class => ResetUserPassword::class,
        UpdatesUserProfiles::class => UpdateUserProfile::class,
        UpdatesUserPasswords::class => UpdateUserPassword::class,
        DeletesUsers::class => DeleteUser::class,
    ];

    /**
     * Login middleware pipeline.
     *
     * @var array
     */
    public static $loginPipeline = [
        // EnsureLoginIsNotThrottled::class,
        RedirectIfTwoFactorAuthenticatable::class,
        AttemptToAuthenticate::class,
        PrepareAuthenticatedSession::class,
        AfterAuthenticating::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        $this->registerAuthActionAfterHooks();
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

    /**
     * Register actions to erform after every major authentication events.
     *
     * @return void
     */
    protected function registerAuthActionAfterHooks(): void
    {
        // Actions to perform after new user has been
        // created and saved to the database.
        CreateNewUser::afterCreatingUser(fn () => null);

        // Actions to perform after an existing user
        // has been successfully authenticated.
        Authenticator::afterAuthentication(fn () => null);
    }
}
