<?php

namespace Emberfuse\Blaze\Console;

use Throwable;
use Illuminate\Console\Command;
use Emberfuse\Blaze\Installer\Util;
use Emberfuse\Blaze\Installer\Stubs;
use Emberfuse\Blaze\Installer\NpmPackages;
use Emberfuse\Blaze\Installer\ComposerPackages;
use Emberfuse\Blaze\Console\Traits\InteractsWithConsole;

class InstallCommand extends Command
{
    use InteractsWithConsole;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'blaze:install {--composer=global : Absolute path to the Composer binary which should be used to install packages}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install the Blaze components and resources';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Publish...
        $this->publishVendor();

        // Install Scorch...
        $this->installScorch();

        // Configure Session...
        $this->configureSession();

        // Inertia Stack...
        $this->installInertiaStack();

        return 0;
    }

    /**
     * Publish blaze replacements.
     *
     * @return void
     */
    protected function publishVendor(): void
    {
        $this->callSilent('vendor:publish', ['--tag' => 'blaze-config', '--force' => true]);
        $this->callSilent('vendor:publish', ['--tag' => 'blaze-support', '--force' => true]);
        $this->callSilent('vendor:publish', ['--tag' => 'blaze-resources', '--force' => true]);
        $this->callSilent('vendor:publish', ['--tag' => 'blaze-routes', '--force' => true]);
        $this->callSilent('vendor:publish', ['--tag' => 'blaze-tests', '--force' => true]);
        $this->callSilent('vendor:publish', ['--tag' => 'blaze-shell', '--force' => true]);
        $this->callSilent('vendor:publish', ['--tag' => 'blaze-ci', '--force' => true]);
        $this->callSilent('vendor:publish', ['--tag' => 'blaze-seeders', '--force' => true]);
        $this->callSilent('vendor:publish', ['--tag' => 'blaze-factories', '--force' => true]);
        $this->callSilent('vendor:publish', ['--tag' => 'blaze-migrations', '--force' => true]);
    }

    /**
     * Install and publish Scorch resources.
     *
     * @return void
     */
    protected function installScorch(): void
    {
        $this->callSilent('scorch:install');
    }

    /**
     * Configure the session driver for Blaze.
     *
     * @return void
     */
    protected function configureSession(): void
    {
        if (! class_exists('CreateSessionsTable')) {
            try {
                $this->callSilent('session:table');
            } catch (Throwable $e) {
                $this->error($e->getMessage());
            }
        }

        Util::replaceInFile("'SESSION_DRIVER', 'file'", "'SESSION_DRIVER', 'database'", config_path('session.php'));
        Util::replaceInFile('SESSION_DRIVER=file', 'SESSION_DRIVER=database', base_path('.env'));
        Util::replaceInFile('SESSION_DRIVER=file', 'SESSION_DRIVER=database', base_path('.env.example'));
    }

    /**
     * Install the Inertia stack into the application.
     *
     * @return void
     */
    protected function installInertiaStack(): void
    {
        // Install Composer and NPM packages...
        (new ComposerPackages($this))->installPackages();
        (new NpmPackages($this))->installPackages();

        // Tailwind and JS Configuration...
        Stubs::copyAppConfigurations();

        // Directories...
        Stubs::ensureDirectoriesExists();

        // Actions...
        Stubs::copyActions();

        // Service Providers...
        Stubs::copyServiceProviders();

        // Inertia Views...
        Stubs::copyInertiaStubs();

        // Install Inertia Middleware...
        $this->runProcess(['php', 'artisan', 'inertia:middleware', 'HandleInertiaRequests', '--force'], base_path());
        Util::installMiddlewareAfter('SubstituteBindings::class', '\App\Http\Middleware\HandleInertiaRequests::class');
        Util::replaceInFile(
            '// \Illuminate\Session\Middleware\AuthenticateSession::class',
            '\Emberfuse\Blaze\Http\Middleware\AuthenticateSession::class',
            app_path('Http/Kernel.php')
        );

        // Restructure Project Directory...
        Stubs::removeRedundancies();

        // Run Project Setup Procedures...
        $this->runProcess(['chmod', '+x', 'bin/setup.sh'], base_path());
        // $this->runProcess(['bin/setup.sh'], base_path());

        // Generate Application Key...
        $this->callSilent('key:generate');

        // Completion Message...
        $this->line('');
        $this->info('Blaze scaffolding installed successfully.');
        $this->comment('Please make sure the application key is set, if not generate a new one using "php artisan key:generate".');
        $this->comment('Please execute "npm install && npm run dev" to build your assets.');
    }
}
