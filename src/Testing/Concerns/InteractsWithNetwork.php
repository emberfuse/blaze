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
        $connected = fsockopen('www.example.com', $port);

        if ($connected) {
            fclose($connected);

            return true;
        }

        return false;
    }
}
