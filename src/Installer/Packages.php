<?php

namespace Emberfuse\Blaze\Installer;

use Illuminate\Console\Command;

abstract class Packages
{
    /**
     * Create new instance of pcakage installer.
     *
     * @param \Illuminate\Console\Command $command
     *
     * @return void
     */
    public function __construct(Command $command)
    {
        $this->command = $command;
    }

    /**
     * Installs the given Packages into the application.
     *
     * @param mixed|null $packages
     *
     * @return void
     */
    abstract public function installPackages($packages = null): void;
}
