<?php

namespace Tests\Concerns;

trait ChecksForInternet
{
    /**
     * Determine if an Internet connection is available.
     *
     * @return bool
     */
    protected function isConnected(): bool
    {
        if (@fsockopen('www.example.com', 80)) {
            @fclose();

            return true;
        }

        return false;
    }
}
