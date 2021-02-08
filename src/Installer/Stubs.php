<?php

namespace Cratespace\Preflight\Installer;

use Illuminate\Filesystem\Filesystem;

class Stubs
{
    /**
     * Copy over app development and js configuration files.
     *
     * @return void
     */
    public static function copyAppConfigurations(): void
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

    /**
     * Remove uneccessary files.
     *
     * @return void
     */
    public static function removeRedundancies(): void
    {
        if (file_exists($styleCi = base_path('.styleci.yml'))) {
            unlink($styleCi);
        }

        if (file_exists(resource_path('views/welcome.blade.php'))) {
            chmod(resource_path('views/welcome.blade.php'), 0644);

            unlink(resource_path('views/welcome.blade.php'));
        }

        if (file_exists(base_path('README.md'))) {
            unlink(base_path('README.md'));
        }

        (new Filesystem())->deleteDirectory(resource_path('sass'));

        unlink(resource_path('js/bootstrap.js'));
    }

    /**
     * Ensure listed directories exist or else create them.
     *
     * @return void
     */
    public static function ensureDirectoriesExists(): void
    {
        (new Filesystem())->ensureDirectoryExists(app_path('Actions/Sentinel'));
        (new Filesystem())->ensureDirectoryExists(public_path('css'));
        (new Filesystem())->ensureDirectoryExists(resource_path('css'));
        (new Filesystem())->ensureDirectoryExists(resource_path('js/Tests'));
        (new Filesystem())->ensureDirectoryExists(resource_path('js/Config'));
        (new Filesystem())->ensureDirectoryExists(resource_path('js/Plugins'));
        (new Filesystem())->ensureDirectoryExists(resource_path('js/Views'));
        (new Filesystem())->ensureDirectoryExists(resource_path('js/Views/Layouts'));
        (new Filesystem())->ensureDirectoryExists(resource_path('js/Views/Components'));
        (new Filesystem())->ensureDirectoryExists(resource_path('js/Views/API'));
        (new Filesystem())->ensureDirectoryExists(resource_path('js/Views/Auth'));
        (new Filesystem())->ensureDirectoryExists(resource_path('js/Views/Profile'));
        (new Filesystem())->ensureDirectoryExists(resource_path('js/Views/Business'));
        (new Filesystem())->ensureDirectoryExists(resource_path('js/Views/Marketing'));
        (new Filesystem())->ensureDirectoryExists(resource_path('js/Views/General'));
        (new Filesystem())->ensureDirectoryExists(resource_path('views'));
        (new Filesystem())->ensureDirectoryExists(resource_path('markdown'));
    }

    /**
     * Copy over inertial vue js templates for views.
     *
     * @return void
     */
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

    /**
     * Copy over service provider stubs.
     *
     * @return void
     */
    public static function copyServiceProviders(): void
    {
        copy(
            __DIR__ . '/../../stubs/app/Providers/PreflightServiceProvider.php',
            app_path('Providers/PreflightServiceProvider.php')
        );

        Util::installServiceProviderAfter('RouteServiceProvider', 'SentinelServiceProvider');
        Util::installServiceProviderAfter('SentinelServiceProvider', 'PreflightServiceProvider');
    }
}
