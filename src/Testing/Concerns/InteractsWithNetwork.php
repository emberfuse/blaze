<?php

namespace Cratespace\Preflight\Testing\Concerns;

trait InteractsWithNetwork
{
    /**
     * Determine if an Internet connection is available.
     *
     * @param int $port
     *
     * @return bool
     */
    protected function isConnected(int $port = 80): bool
    {
        if (@fsockopen('http://example.com/', $port)) {
            @fclose();

            return true;
        }

        return false;
    }
}
