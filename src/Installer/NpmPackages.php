<?php

namespace Cratespace\Preflight\Installer;

use Closure;
use Illuminate\Filesystem\Filesystem;

class NpmPackages extends Packages
{
    /**
     * Preflight specific node modules.
     *
     * @var array
     */
    protected $nodeModules = [
        '@inertiajs/inertia' => '^0.8.7',
        '@inertiajs/inertia-vue3' => '^0.3.14',
        '@inertiajs/progress' => '^0.2.4',
        '@tailwindcss/forms' => '^0.3.2',
        '@tailwindcss/typography' => '^0.4.0',
        'postcss-import' => '^14.0.1',
        'tailwindcss' => '^2.1.2',
        'autoprefixer' => '^10.2.5',
        'moment' => '^2.29.1',
        'browser-sync' => '^2.26.14',
        'browser-sync-webpack-plugin' => '^2.3.0',
        'vue' => '^3.0.11',
        '@vue/compiler-sfc' => '^3.0.11',
        'vue-loader' => '^16.2.0',
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
        $this->updateNodePackages(function ($nativePackages) use ($packages) {
            $packages = is_null($packages) ? $this->nodeModules : $packages;

            return $packages + $nativePackages;
        }, 'devDependencies');

        $this->updateNodePackages(function ($nativeScripts) {
            return [
                'test' => 'jest --verbose ./resources/js/Tests',
            ] + $nativeScripts;
        }, 'scripts');
    }

    /**
     * Update the "package.json" file.
     *
     * @param \Closure $callback
     * @param string   $configurationKey
     *
     * @return void
     */
    public function updateNodePackages(Closure $callback, string $configurationKey): void
    {
        if (! file_exists(base_path('package.json'))) {
            return;
        }

        $packages = $this->getPackageConfig();

        $packages[$configurationKey] = $callback(
            array_key_exists($configurationKey, $packages) ? $packages[$configurationKey] : [],
            $configurationKey
        );

        ksort($packages[$configurationKey]);

        $this->setPackageConfig($packages);

        $this->flushNodeModules();
    }

    /**
     * Get package.json contents.
     *
     * @return array
     */
    protected function getPackageConfig(): array
    {
        return json_decode(file_get_contents(base_path('package.json')), true);
    }

    /**
     * Write to package.json file.
     *
     * @param array $content
     *
     * @return void
     */
    protected function setPackageConfig(array $content): void
    {
        file_put_contents(
            base_path('package.json'),
            json_encode($content, \JSON_UNESCAPED_SLASHES | \JSON_PRETTY_PRINT) . \PHP_EOL
        );
    }

    /**
     * Delete the "node_modules" directory and remove the associated lock files.
     *
     * @return void
     */
    public function flushNodeModules(): void
    {
        tap(new Filesystem(), function ($files) {
            $files->deleteDirectory(base_path('node_modules'));

            $files->delete(base_path('yarn.lock'));
            $files->delete(base_path('package-lock.json'));
        });
    }
}
