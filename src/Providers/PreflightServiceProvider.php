<?php

namespace Cratespace\Preflight\Providers;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Route;
use Cratespace\Sentinel\Sentinel\View;
use Illuminate\Support\ServiceProvider;
use Cratespace\Preflight\Installer\Util;
use App\Http\Middleware\HandleInertiaRequests;
use Cratespace\Preflight\Console\InstallCommand;
use Cratespace\Preflight\Console\ActionMakeCommand;
use Cratespace\Preflight\Console\ProjectSetupCommand;
use Cratespace\Preflight\Console\PublishConfigJsCommand;
use Cratespace\Preflight\Console\SeedDefaultUserCommand;
use Cratespace\Preflight\Http\Middleware\ShareInertiaData;
use Cratespace\Sentinel\Sentinel\Config as SentinelConfig;

class PreflightServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
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
        ], 'preflight-factories');

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
            'domain' => SentinelConfig::domain(),
            'prefix' => SentinelConfig::prefix(),
        ], function (): void {
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
            ActionMakeCommand::class,
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

        $this->configureSentinelViews();
    }

    /**
     * Register authentication module views.
     *
     * @return void
     */
    protected function configureSentinelViews(): void
    {
        View::login(function (Request $request): Response {
            return Inertia::render('Auth/Login', [
                'canResetPassword' => Route::has('password.request'),
                'status' => $request->session()->get('status'),
            ]);
        });

        View::twoFactorChallenge(function (Request $request): Response {
            return Inertia::render('Auth/TwoFactorChallenge', [
                'status' => $request->session()->get('status'),
            ]);
        });

        View::requestPasswordResetLink(function (Request $request): Response {
            return Inertia::render('Auth/ForgotPassword', [
                'status' => $request->session()->get('status'),
            ]);
        });

        View::resetPassword(function (Request $request): Response {
            return Inertia::render('Auth/ResetPassword', [
                'email' => $request->input('email'),
                'token' => $request->route('token'),
            ]);
        });

        View::register(function (): Response {
            return Inertia::render('Auth/Register');
        });

        View::verifyEmail(function (Request $request): Response {
            return Inertia::render('Auth/VerifyEmail', [
                'status' => $request->session()->get('status'),
            ]);
        });

        View::userProfile(function (Request $request): Response {
            return Inertia::render('Profile/Show', [
                'user' => $request->user(),
            ]);
        });
    }
}
