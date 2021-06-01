<?php

namespace Emberfuse\Blaze\Installer;

use Symfony\Component\Process\Process;

class ComposerPackages extends Packages
{
    /**
     * List of blaze specific composer packages.
     *
     * @var array
     */
    protected $packages = [
        'inertiajs/inertia-laravel:^0.4.2',
        'emberfuse/scorch:^1.0',
        'tightenco/ziggy:^1.2',
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

        (new Process($command, base_path(), ['COMPOSER_MEMORY_LIMIT' => '-1']))
            ->setTimeout(null)
            ->run(function ($type, $output) {
                $this->command->getOutput()->write($output);
            });
    }
}
