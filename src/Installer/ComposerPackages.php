<?php

namespace Cratespace\Preflight\Installer;

use Cratespace\Preflight\Console\Concerns\InteractsWithTerminal;

class ComposerPackages extends Packages
{
    use InteractsWithTerminal;

    /**
     * List of preflight specific composer packages.
     *
     * @var array
     */
    protected $packages = [
        'inertiajs/inertia-laravel:^0.3.5',
        'laravel/sanctum:^2.8',
        'tightenco/ziggy:^1.0',
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
        $composer = $this->command->option('composer');

        if ($composer !== 'global') {
            $command = ['php', $composer, 'require'];
        }

        $command = array_merge(
            $command ?? ['composer', 'require'],
            is_array($packages) ? $packages : $this->packages
        );

        $this->runProcess($command, base_path(), ['COMPOSER_MEMORY_LIMIT' => '-1']);
    }
}
