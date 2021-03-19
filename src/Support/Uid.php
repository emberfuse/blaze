<?php

namespace Cratespace\Preflight\Support;

abstract class Uid
{
    /**
     * String of acceptable characters.
     *
     * @var string
     */
    protected static $characterPool = 'ABCDEFGHJKLMNOPQRSTUVWXYZ23456789';

    /**
     * Default UID character length.
     */
    public const CHARACTER_LENGTH = 24;

    /**
     * Generate a new and unique code.
     *
     * @param array[] $arguments
     *
     * @return string
     */
    abstract public static function generate(...$arguments): string;
}
