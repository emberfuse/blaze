<?php

namespace Cratespace\Preflight\Console\Concerns;

use Symfony\Component\Process\Process;

trait InteractsWithTerminal
{
    /**
     * Run given command using given arguments.
     *
     * @param array          $command
     * @param string|null    $cwd
     * @param array|null     $env
     * @param mixed|null     $input
     * @param int|float|null $timeout
     *
     * @throws \LogicException
     */
    public function runProcess(array $command, ?string $cwd = null, ?array $env = null, $input = null, ?float $timeout = null)
    {
        (new Process($command, $cwd, $env))
            ->setTimeout($timeout)
            ->run(function ($type, $output) {
                $this->output->write($output);
            });
    }
}
