<?php

namespace Emberfuse\Blaze\Providers;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Route;
use Emberfuse\Scorch\Scorch\View;
use Illuminate\Support\ServiceProvider;
use App\Http\Middleware\HandleInertiaRequests;
use Emberfuse\Blaze\Console\InstallCommand;
use Emberfuse\Blaze\Console\QueryMakeCommand;
use Emberfuse\Blaze\Console\ActionMakeCommand;
use Emberfuse\Blaze\Console\FilterMakeCommand;
use Emberfuse\Blaze\Console\ProjectSetupCommand;
use Emberfuse\Blaze\Console\PresenterMakeCommand;
use Emberfuse\Blaze\Console\PublishConfigJsCommand;
use Emberfuse\Blaze\Console\SeedDefaultUserCommand;
use Emberfuse\Blaze\Http\Middleware\ShareInertiaData;
use Emberfuse\Scorch\Scorch\Config as ScorchConfig;

class BlazeServiceProvider extends ServiceProvider
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
        ], 'blaze-config');

        $this->publishes([
            __DIR__ . '/../../stubs/app/Actions/API/CreateNewApiToken.php' => app_path('Actions/API/CreateNewApiToken.php'),
            __DIR__ . '/../../stubs/app/Actions/API/UpdateApiToken.php' => app_path('Actions/API/UpdateApiToken.php'),
        ], 'blaze-support');

        $this->publishes([
            __DIR__ . '/../../stubs/resources' => base_path('resources'),
        ], 'blaze-resources');

        $this->publishes([
            __DIR__ . '/../../stubs/routes/web.php' => base_path('routes/web.php'),
        ], 'blaze-routes');

        $this->publishes([
            __DIR__ . '/../../stubs/tests' => base_path('tests'),
        ], 'blaze-tests');

        $this->publishes([
            __DIR__ . '/../../stubs/bin' => base_path('bin'),
        ], 'blaze-shell');

        $this->publishes([
            __DIR__ . '/../../stubs/.github' => base_path('.github'),
        ], 'blaze-ci');

        $this->publishes([
            __DIR__ . '/../../database/migrations' => database_path('migrations'),
        ], 'blaze-migrations');

        $this->publishes([
            __DIR__ . '/../../database/factories' => database_path('factories'),
        ], 'blaze-factories');

        $this->publishes([
            __DIR__ . '/../../database/seeders' => database_path('seeders'),
        ], 'blaze-seeders');
    }

    /**
     * Configure the routes offered by the application.
     *
     * @return void
     */
    protected function configureRoutes(): void
    {
        Route::group([
            'namespace' => 'Emberfuse\Blaze\Http\Controllers',
            'domain' => ScorchConfig::domain(),
            'prefix' => ScorchConfig::prefix(),
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
            FilterMakeCommand::class,
            PresenterMakeCommand::class,
            QueryMakeCommand::class,
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

        $this->configureScorchViews();
    }

    /**
     * Register authentication module views.
     *
     * @return void
     */
    protected function configureScorchViews(): void
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

        View::register(function ($request): Response {
            return Inertia::render('Auth/Register', [
                'query' => $request->query(),
            ]);
        });

        View::verifyEmail(function (Request $request): Response {
            return Inertia::render('Auth/VerifyEmail', [
                'status' => $request->session()->get('status'),
            ]);
        });

        View::confirmPassword(function () {
            return Inertia::render('Auth/ConfirmPassword');
        });

        View::userProfile(function (Request $request): Response {
            return Inertia::render('Profile/Show', [
                'user' => $request->user(),
            ]);
        });
    }
}
