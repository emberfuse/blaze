<?php

namespace Cratespace\Preflight\Support\Traits;

use Illuminate\Log\LogManager;

trait Loggable
{
    /**
     * Log a error message to the logs.
     *
     * @param string|null $message
     * @param array       $context
     *
     * @return void
     */
    public function logError(string $message, array $context = []): void
    {
        $this->logger()->error($message, $context);
    }

    /**
     * Log a debug message to the logs.
     *
     * @param string|null $message
     * @param array       $context
     *
     * @return void
     */
    public function logDebug(string $message, array $context = []): void
    {
        $this->logger()->debug($message, $context);
    }

    /**
     * Write some information to the log.
     *
     * @param string|null $message
     * @param array       $context
     *
     * @return void
     */
    public function logInfo(string $message, array $context = []): void
    {
        $this->logger()->info($message, $context);
    }

    /**
     * Log a debug message to the logs or get the logger instance.
     *
     * @param string|null $message
     * @param array       $context
     *
     * @return \Illuminate\Log\LogManager|null
     */
    public function logger(?string $message = null, array $context = []): ?LogManager
    {
        return logger($message, $context);
    }
}
