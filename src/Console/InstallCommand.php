<?php

namespace Cratespace\Preflight\Console;

use Throwable;
use Illuminate\Console\Command;
use Cratespace\Preflight\Installer\Util;
use Cratespace\Preflight\Installer\Stubs;
use Cratespace\Preflight\Installer\NpmPackages;
use Cratespace\Preflight\Installer\ComposerPackages;
use Cratespace\Preflight\Installer\ProjectStructure;
use Cratespace\Preflight\Console\Traits\InteractsWithConsole;

class InstallCommand extends Command
{
    use InteractsWithConsole;

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
     * @return int
     */
    public function handle()
    {
        // Publish...
        $this->publishVendor();

        // Install Sentinel...
        $this->installSentinel();

        // Configure Session...
        $this->configureSession();

        // Inertia Stack...
        $this->installInertiaStack();

        return 0;
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
        $this->callSilent('vendor:publish', ['--tag' => 'preflight-seeders', '--force' => true]);
        $this->callSilent('vendor:publish', ['--tag' => 'preflight-factories', '--force' => true]);
    }

    /**
     * Install and publish Sentinel resources.
     *
     * @return void
     */
    protected function installSentinel(): void
    {
        $this->callSilent('citadel:install');
    }

    /**
     * Configure the session driver for Preflight.
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

        // Service Providers...
        Stubs::copyServiceProviders();

        // Inertia Views...
        Stubs::copyInertiaViews();

        // Install Inertia Middleware...
        $this->runProcess(['php', 'artisan', 'inertia:middleware', 'HandleInertiaRequests', '--force'], base_path());
        Util::installMiddlewareAfter('SubstituteBindings::class', '\App\Http\Middleware\HandleInertiaRequests::class');

        // Restructure Project Directory...
        ProjectStructure::restructureProjectDirectory();

        // Install Sanctum...
        $this->runProcess(['php', 'artisan', 'vendor:publish', '--provider=Laravel\Sanctum\SanctumServiceProvider', '--force'], base_path());

        // Run Project Setup Procedures...
        $this->runProcess(['chmod', '+x', 'bin/setup.sh'], base_path());
        $this->runProcess(['bin/setup.sh'], base_path());

        // Generate Application Key...
        $this->callSilent('key:generate');

        // Completion Message...
        $this->line('');
        $this->info('Preflight scaffolding installed successfully.');
        $this->comment('Please make sure the application key is set, if not generate a new one using "php artisan key:generate".');
        $this->comment('Please execute "npm install && npm run dev" to build your assets.');
    }
}
