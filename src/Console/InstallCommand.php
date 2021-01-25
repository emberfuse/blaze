<?php

namespace Cratespace\Preflight\Console;

use Throwable;
use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Process\Process;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'preflight:install {--composer=global : Absolute path to the Composer binary which should be used to install packages}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install the Preflight components and resources';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        // Publish...
        $this->publishVendor();
        $this->installCitadel();

        // Configure Session...
        $this->configureSession();

        // AuthenticateSession Middleware...
        $this->replaceInFile(
            '// \Illuminate\Session\Middleware\AuthenticateSession::class',
            '\Cratespace\Preflight\Http\Middleware\AuthenticateSession::class',
            app_path('Http/Kernel.php')
        );

        // Inertia Stack...
        $this->installInertiaStack();
    }

    /**
     * Publish preflight replacements.
     *
     * @return void
     */
    protected function publishVendor(): void
    {
        $this->callSilent('vendor:publish', ['--tag' => 'preflight-config', '--force' => true]);
        $this->callSilent('vendor:publish', ['--tag' => 'preflight-resources', '--force' => true]);
        $this->callSilent('vendor:publish', ['--tag' => 'preflight-routes', '--force' => true]);
        $this->callSilent('vendor:publish', ['--tag' => 'preflight-tests', '--force' => true]);
        $this->callSilent('vendor:publish', ['--tag' => 'preflight-shell', '--force' => true]);
        $this->callSilent('vendor:publish', ['--tag' => 'preflight-ci', '--force' => true]);
        $this->callSilent('vendor:publish', ['--tag' => 'preflight-database', '--force' => true]);
        $this->callSilent('vendor:publish', ['--tag' => 'preflight-seeders', '--force' => true]);
    }

    /**
     * Install and publish Citadel resources.
     *
     * @return void
     */
    protected function installCitadel(): void
    {
        $this->callSilent('citadel:install');
    }

    /**
     * Configure the session driver for Jetstream.
     *
     * @return void
     */
    protected function configureSession(): void
    {
        if (! class_exists('CreateSessionsTable')) {
            try {
                $this->call('session:table');
            } catch (Throwable $e) {
                $this->error($e->getMessage());
            }
        }

        $this->replaceInFile("'SESSION_DRIVER', 'file'", "'SESSION_DRIVER', 'database'", config_path('session.php'));
        $this->replaceInFile('SESSION_DRIVER=file', 'SESSION_DRIVER=database', base_path('.env'));
        $this->replaceInFile('SESSION_DRIVER=file', 'SESSION_DRIVER=database', base_path('.env.example'));
    }

    /**
     * Install the Inertia stack into the application.
     *
     * @return void
     */
    protected function installInertiaStack(): void
    {
        // Install Inertia...
        $this->requireComposerPackages(
            'inertiajs/inertia-laravel:^0.3.5',
            'laravel/sanctum:^2.8',
            'tightenco/ziggy:^1.0'
        );

        // Install NPM packages...
        $this->updateNodePackages(function ($packages) {
            return [
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
            ] + $packages;
        });

        // Sanctum...
        (new Process(['php', 'artisan', 'vendor:publish', '--provider=Laravel\Sanctum\SanctumServiceProvider', '--force'], base_path()))
                ->setTimeout(null)
                ->run(fn ($type, $output) => $this->output->write($output));

        // Tailwind and JS Configuration...
        copy(__DIR__ . '/../../stubs/tailwind.config.js', base_path('tailwind.config.js'));
        copy(__DIR__ . '/../../stubs/webpack.mix.js', base_path('webpack.mix.js'));
        copy(__DIR__ . '/../../stubs/postcss.config.js', base_path('postcss.config.js'));
        copy(__DIR__ . '/../../stubs/jest.config.js', base_path('jest.config.js'));
        copy(__DIR__ . '/../../stubs/jsconfig.json', base_path('jsconfig.json'));
        copy(__DIR__ . '/../../stubs/.babelrc', base_path('.babelrc'));
        copy(__DIR__ . '/../../stubs/.npmignore', base_path('.npmignore'));
        copy(__DIR__ . '/../../stubs/.eslintrc.js', base_path('.eslintrc.js'));

        // App Configurations...
        if (file_exists(base_path('phpunit.xml'))) {
            unlink(base_path('phpunit.xml'));

            copy(__DIR__ . '/../../stubs/phpunit.xml', base_path('phpunit.xml'));
        }

        if (file_exists(base_path('.env.example'))) {
            unlink(base_path('.env.example'));

            copy(__DIR__ . '/../../stubs/.env.example', base_path('.env.example'));
        }

        // Directories...
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

        (new Filesystem())->deleteDirectory(resource_path('sass'));

        // Terms Of Service / Privacy Policy...
        copy(__DIR__ . '/../../stubs/resources/markdown/terms.md', resource_path('markdown/terms.md'));
        copy(__DIR__ . '/../../stubs/resources/markdown/policy.md', resource_path('markdown/policy.md'));

        // Service Providers...
        copy(__DIR__ . '/../../stubs/app/Providers/PreflightServiceProvider.php', app_path('Providers/PreflightServiceProvider.php'));
        $this->installServiceProviderAfter('RouteServiceProvider', 'CitadelServiceProvider');
        $this->installServiceProviderAfter('CitadelServiceProvider', 'PreflightServiceProvider');

        // Middleware...
        (new Process(['php', 'artisan', 'inertia:middleware', 'HandleInertiaRequests', '--force'], base_path()))
            ->setTimeout(null)
            ->run(fn ($type, $output) => $this->output->write($output));

        $this->installMiddlewareAfter('SubstituteBindings::class', '\App\Http\Middleware\HandleInertiaRequests::class');

        // Blade Views...
        copy(__DIR__ . '/../../stubs/resources/views/app.blade.php', resource_path('views/app.blade.php'));

        if (file_exists(resource_path('views/welcome.blade.php'))) {
            chmod(resource_path('views/welcome.blade.php'), 0644);

            unlink(resource_path('views/welcome.blade.php'));
        }

        // Inertia Pages...
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

        // Routes...
        $this->replaceInFile('auth:api', 'auth:sanctum', base_path('routes/api.php'));

        copy(__DIR__ . '/../../stubs/routes/web.php', base_path('routes/web.php'));

        // Assets...
        copy(__DIR__ . '/../../stubs/resources/css/app.css', resource_path('css/app.css'));
        copy(__DIR__ . '/../../stubs/resources/js/app.js', resource_path('js/app.js'));

        // Flush node_modules...
        // static::flushNodeModules();

        $this->line('');
        $this->info('Preflight scaffolding installed successfully.');
        $this->comment('Please execute "npm install && npm run dev" to build your assets.');
    }

    /**
     * Install the service provider in the application configuration file.
     *
     * @param string $after
     * @param string $name
     *
     * @return void
     */
    protected function installServiceProviderAfter(string $after, string $name): void
    {
        if (! Str::contains($appConfig = file_get_contents(config_path('app.php')), 'App\\Providers\\' . $name . '::class')) {
            file_put_contents(config_path('app.php'), str_replace(
                'App\\Providers\\' . $after . '::class,',
                'App\\Providers\\' . $after . '::class,' . \PHP_EOL . '        App\\Providers\\' . $name . '::class,',
                $appConfig
            ));
        }
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
    protected function installMiddlewareAfter(string $after, string $name, string $group = 'web'): void
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
     * Installs the given Composer Packages into the application.
     *
     * @param mixed $packages
     *
     * @return void
     */
    protected function requireComposerPackages($packages): void
    {
        $composer = $this->option('composer');

        if ($composer !== 'global') {
            $command = ['php', $composer, 'require'];
        }

        $command = array_merge(
            $command ?? ['composer', 'require'],
            is_array($packages) ? $packages : func_get_args()
        );

        (new Process($command, base_path(), ['COMPOSER_MEMORY_LIMIT' => '-1']))
            ->setTimeout(null)
            ->run(fn ($type, $output) => $this->output->write($output));
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
    }

    /**
     * Delete the "node_modules" directory and remove the associated lock files.
     *
     * @return void
     */
    protected static function flushNodeModules(): void
    {
        tap(new Filesystem(), function ($files) {
            $files->deleteDirectory(base_path('node_modules'));

            $files->delete(base_path('yarn.lock'));
            $files->delete(base_path('package-lock.json'));
        });
    }

    /**
     * Replace a given string within a given file.
     *
     * @param string $search
     * @param string $replace
     * @param string $path
     *
     * @return void
     */
    protected function replaceInFile(string $search, string $replace, string $path): void
    {
        file_put_contents($path, str_replace($search, $replace, file_get_contents($path)));
    }
}
