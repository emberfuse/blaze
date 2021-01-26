<?php

namespace Cratespace\Preflight\Installer;

use Illuminate\Filesystem\Filesystem;

class NpmPackages extends Packages
{
    /**
     * Preflight specific node modules.
     *
     * @var array
     */
    protected $nodeModules = [
        '@inertiajs/inertia' => '^0.8.2',
        '@inertiajs/inertia-vue' => '^0.5.4',
        '@inertiajs/progress' => '^0.2.4',
        '@tailwindcss/forms' => '^0.2.1',
        '@tailwindcss/typography' => '^0.3.0',
        'portal-vue' => '^2.1.7',
        'postcss-import' => '^12.0.1',
        'tailwindcss' => '^2.0.1',
        '@vue/test-utils' => '^1.1.0',
        'autoprefixer' => '^10.0.2',
        'moment' => '^2.29.1',
        'babel-core' => '7.0.0-bridge.0',
        'babel-jest' => '^26.5.0',
        'browser-sync' => '^2.23.7',
        'browser-sync-webpack-plugin' => '^2.0.1',
        'jest' => '^26.5.0',
        'vue' => '^2.6.12',
        'vue-loader' => '^15.9.6',
        'vue-template-compiler' => '^2.6.10',
    ];

    /**
     * Installs the given Packages into the application.
     *
     * @param mixed|null $packages
     *
     * @return void
     */
    public function installPackages($packages = null): void
    {
        $this->updateNodePackages(function ($packages) {
            return $this->nodeModules + $packages;
        });
    }

    /**
     * Update the "package.json" file.
     *
     * @param callable $callback
     * @param bool     $dev
     *
     * @return void
     */
    protected static function updateNodePackages(callable $callback, bool $dev = true): void
    {
        if (! file_exists(base_path('package.json'))) {
            return;
        }

        $configurationKey = $dev ? 'devDependencies' : 'dependencies';

        $packages = json_decode(file_get_contents(base_path('package.json')), true);

        $packages[$configurationKey] = $callback(
            array_key_exists($configurationKey, $packages) ? $packages[$configurationKey] : [],
            $configurationKey
        );

        ksort($packages[$configurationKey]);

        file_put_contents(
            base_path('package.json'),
            json_encode($packages, \JSON_UNESCAPED_SLASHES | \JSON_PRETTY_PRINT) . \PHP_EOL
        );

        static::flushNodeModules();
    }

    /**
     * Delete the "node_modules" directory and remove the associated lock files.
     *
     * @return void
     */
    public static function flushNodeModules(): void
    {
        tap(new Filesystem(), function ($files) {
            $files->deleteDirectory(base_path('node_modules'));

            $files->delete(base_path('yarn.lock'));
            $files->delete(base_path('package-lock.json'));
        });
    }
}
