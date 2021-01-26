<?php

namespace Cratespace\Preflight\Installer;

use Illuminate\Support\Str;
use Illuminate\Filesystem\Filesystem;
use Cratespace\Preflight\Console\Concerns\InteractsWithTerminal;

class Stubs
{
    use InteractsWithTerminal;

    public static function copyAppConfigurations()
    {
        copy(__DIR__ . '/../../stubs/tailwind.config.js', base_path('tailwind.config.js'));
        copy(__DIR__ . '/../../stubs/webpack.mix.js', base_path('webpack.mix.js'));
        copy(__DIR__ . '/../../stubs/postcss.config.js', base_path('postcss.config.js'));
        copy(__DIR__ . '/../../stubs/jest.config.js', base_path('jest.config.js'));
        copy(__DIR__ . '/../../stubs/jsconfig.json', base_path('jsconfig.json'));
        copy(__DIR__ . '/../../stubs/.babelrc', base_path('.babelrc'));
        copy(__DIR__ . '/../../stubs/.npmignore', base_path('.npmignore'));
        copy(__DIR__ . '/../../stubs/.eslintrc.js', base_path('.eslintrc.js'));
        copy(__DIR__ . '/../../stubs/resources/css/app.css', resource_path('css/app.css'));
        copy(__DIR__ . '/../../stubs/resources/js/app.js', resource_path('js/app.js'));
    }

    public static function ensureDirectoriesExists(): void
    {
        (new Filesystem())->ensureDirectoryExists(app_path('Actions/Citadel'));
        (new Filesystem())->ensureDirectoryExists(public_path('css'));
        (new Filesystem())->ensureDirectoryExists(resource_path('css'));
        (new Filesystem())->ensureDirectoryExists(resource_path('js/Tests'));
        (new Filesystem())->ensureDirectoryExists(resource_path('js/Config'));
        (new Filesystem())->ensureDirectoryExists(resource_path('js/Plugins'));
        (new Filesystem())->ensureDirectoryExists(resource_path('js/Views/Layouts'));
        (new Filesystem())->ensureDirectoryExists(resource_path('js/Views/Components'));
        (new Filesystem())->ensureDirectoryExists(resource_path('js/Views'));
        (new Filesystem())->ensureDirectoryExists(resource_path('js/Views/API'));
        (new Filesystem())->ensureDirectoryExists(resource_path('js/Views/Auth'));
        (new Filesystem())->ensureDirectoryExists(resource_path('js/Views/Profile'));
        (new Filesystem())->ensureDirectoryExists(resource_path('js/Views/Business'));
        (new Filesystem())->ensureDirectoryExists(resource_path('js/Views/Marketing'));
        (new Filesystem())->ensureDirectoryExists(resource_path('views'));
        (new Filesystem())->ensureDirectoryExists(resource_path('markdown'));
    }

    public static function copyInertiaViews(): void
    {
        (new Filesystem())->copyDirectory(__DIR__ . '/../../stubs/resources/js/Views/Components', resource_path('js/Views/Components'));
        (new Filesystem())->copyDirectory(__DIR__ . '/../../stubs/resources/js/Views/Layouts', resource_path('js/Views/Layouts'));
        (new Filesystem())->copyDirectory(__DIR__ . '/../../stubs/resources/js/Views/Marketing', resource_path('js/Views/Marketing'));
        (new Filesystem())->copyDirectory(__DIR__ . '/../../stubs/resources/js/Views/General', resource_path('js/Views/General'));
        (new Filesystem())->copyDirectory(__DIR__ . '/../../stubs/resources/js/Views/API', resource_path('js/Views/API'));
        (new Filesystem())->copyDirectory(__DIR__ . '/../../stubs/resources/js/Views/Auth', resource_path('js/Views/Auth'));
        (new Filesystem())->copyDirectory(__DIR__ . '/../../stubs/resources/js/Views/Profile', resource_path('js/Views/Profile'));
        (new Filesystem())->copyDirectory(__DIR__ . '/../../stubs/resources/js/Views/Business', resource_path('js/Views/Business'));
        (new Filesystem())->copyDirectory(__DIR__ . '/../../stubs/resources/js/Tests', resource_path('js/Tests'));
        (new Filesystem())->copyDirectory(__DIR__ . '/../../stubs/resources/js/Config', resource_path('js/Config'));
        (new Filesystem())->copyDirectory(__DIR__ . '/../../stubs/resources/js/Plugins', resource_path('js/Plugins'));

        copy(__DIR__ . '/../../stubs/resources/markdown/terms.md', resource_path('markdown/terms.md'));
        copy(__DIR__ . '/../../stubs/resources/markdown/policy.md', resource_path('markdown/policy.md'));
        copy(__DIR__ . '/../../stubs/resources/views/app.blade.php', resource_path('views/app.blade.php'));
    }

    public static function copyServiceProviders(): void
    {
        copy(
            __DIR__ . '/../../stubs/app/Providers/PreflightServiceProvider.php',
            app_path('Providers/PreflightServiceProvider.php')
        );

        static::installServiceProviderAfter('RouteServiceProvider', 'CitadelServiceProvider');
        static::installServiceProviderAfter('CitadelServiceProvider', 'PreflightServiceProvider');
    }

    /**
     * Install Inertia and Preflight middleware.
     *
     * @return void
     */
    public static function installMiddleware(): void
    {
        // Middleware...
        (new self())->runProcess(['php', 'artisan', 'inertia:middleware', 'HandleInertiaRequests', '--force'], base_path());

        static::installMiddlewareAfter('SubstituteBindings::class', '\App\Http\Middleware\HandleInertiaRequests::class');
    }

    /**
     * Install the middleware to a group in the application Http Kernel.
     *
     * @param string $after
     * @param string $name
     * @param string $group
     *
     * @return void
     */
    protected static function installMiddlewareAfter(string $after, string $name, string $group = 'web'): void
    {
        $httpKernel = file_get_contents(app_path('Http/Kernel.php'));

        $middlewareGroups = Str::before(Str::after($httpKernel, '$middlewareGroups = ['), '];');
        $middlewareGroup = Str::before(Str::after($middlewareGroups, "'$group' => ["), '],');

        if (! Str::contains($middlewareGroup, $name)) {
            $modifiedMiddlewareGroup = str_replace(
                $after . ',',
                $after . ',' . \PHP_EOL . '            ' . $name . ',',
                $middlewareGroup,
            );

            file_put_contents(app_path('Http/Kernel.php'), str_replace(
                $middlewareGroups,
                str_replace($middlewareGroup, $modifiedMiddlewareGroup, $middlewareGroups),
                $httpKernel
            ));
        }
    }

    /**
     * Install the service provider in the application configuration file.
     *
     * @param string $after
     * @param string $name
     *
     * @return void
     */
    protected static function installServiceProviderAfter(string $after, string $name): void
    {
        if (! Str::contains($appConfig = file_get_contents(config_path('app.php')), 'App\\Providers\\' . $name . '::class')) {
            file_put_contents(config_path('app.php'), str_replace(
                'App\\Providers\\' . $after . '::class,',
                'App\\Providers\\' . $after . '::class,' . \PHP_EOL . '        App\\Providers\\' . $name . '::class,',
                $appConfig
            ));
        }
    }
}
