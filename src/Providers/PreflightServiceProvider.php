<?php

namespace Cratespace\Preflight\Providers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Cratespace\Citadel\Citadel\View;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Route;
use Cratespace\Citadel\Citadel\Config;
use Illuminate\Support\ServiceProvider;
use App\Http\Middleware\HandleInertiaRequests;
use Cratespace\Preflight\Console\InstallCommand;
use Cratespace\Preflight\Console\ProjectSetupCommand;
use Cratespace\Preflight\Console\PublishConfigJsCommand;
use Cratespace\Preflight\Console\SeedDefaultUserCommand;
use Cratespace\Preflight\Http\Middleware\ShareInertiaData;

class PreflightServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->configurePublishing();
        $this->configureRoutes();
        $this->configureCommands();
        $this->configureRedirectResponse();

        $this->bootInertia();
    }

    /**
     * Add redirect response macros.
     *
     * @return void
     */
    protected function configureRedirectResponse(): void
    {
        RedirectResponse::macro('banner', function ($message) {
            return $this->with('flash', [
                'bannerStyle' => 'success',
                'banner' => $message,
            ]);
        });

        RedirectResponse::macro('dangerBanner', function ($message) {
            return $this->with('flash', [
                'bannerStyle' => 'danger',
                'banner' => $message,
            ]);
        });
    }

    /**
     * Configure publishing for the package.
     *
     * @return void
     */
    protected function configurePublishing(): void
    {
        $this->publishes([
            __DIR__ . '/../../stubs/config/defaults.php' => config_path('defaults.php'),
        ], 'preflight-config');

        $this->publishes([
            __DIR__ . '/../../stubs/resources' => base_path('resources'),
        ], 'preflight-resources');

        $this->publishes([
            __DIR__ . '/../../stubs/routes/web.php' => base_path('routes/web.php'),
        ], 'preflight-routes');

        $this->publishes([
            __DIR__ . '/../../stubs/tests' => base_path('tests'),
        ], 'preflight-tests');

        $this->publishes([
            __DIR__ . '/../../stubs/bin' => base_path('bin'),
        ], 'preflight-shell');

        $this->publishes([
            __DIR__ . '/../../stubs/.github' => base_path('.github'),
        ], 'preflight-ci');

        $this->publishes([
            __DIR__ . '/../../database/factories' => database_path('factories'),
        ], 'preflight-database');

        $this->publishes([
            __DIR__ . '/../../database/seeders' => database_path('seeders'),
        ], 'preflight-seeders');
    }

    /**
     * Configure the routes offered by the application.
     *
     * @return void
     */
    protected function configureRoutes(): void
    {
        Route::group([
            'namespace' => 'Cratespace\Preflight\Http\Controllers',
            'domain' => Config::domain(),
            'prefix' => Config::prefix(),
        ], function () {
            $this->loadRoutesFrom(__DIR__ . '/../../routes/routes.php');
        });
    }

    /**
     * Configure the commands offered by the application.
     *
     * @return void
     */
    protected function configureCommands(): void
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->commands([
            InstallCommand::class,
            ProjectSetupCommand::class,
            PublishConfigJsCommand::class,
            SeedDefaultUserCommand::class,
        ]);
    }

    /**
     * Boot any Inertia related services.
     *
     * @return void
     */
    protected function bootInertia(): void
    {
        $kernel = $this->app->make(Kernel::class);

        $kernel->appendMiddlewareToGroup('web', ShareInertiaData::class);
        $kernel->appendToMiddlewarePriority(ShareInertiaData::class);

        if (class_exists(HandleInertiaRequests::class)) {
            $kernel->appendToMiddlewarePriority(HandleInertiaRequests::class);
        }

        $this->configureCitadelViews();
    }

    /**
     * Register authentication module views.
     *
     * @return void
     */
    protected function configureCitadelViews(): void
    {
        View::login(function () {
            return Inertia::render('Auth/Login', [
                'canResetPassword' => Route::has('password.request'),
                'status' => session('status'),
            ]);
        });

        View::twoFactorAuthenticationChallenge(function () {
            return Inertia::render('Auth/VerifyEmail', [
                'status' => session('status'),
            ]);
        });

        View::requestPasswordResetLink(function () {
            return Inertia::render('Auth/ForgotPassword', [
                'status' => session('status'),
            ]);
        });

        View::resetPassword(function (Request $request) {
            return Inertia::render('Auth/ResetPassword', [
                'email' => $request->input('email'),
                'token' => $request->route('token'),
            ]);
        });

        View::register(function () {
            return Inertia::render('Auth/Register');
        });

        View::verifyEmail(function () {
            return Inertia::render('Auth/VerifyEmail', [
                'status' => session('status'),
            ]);
        });

        View::userProfile(function () {
            return Inertia::render('Profile/Show', [
                'user' => request()->user(),
            ]);
        });
    }
}
