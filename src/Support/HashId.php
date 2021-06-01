<?php

namespace Emberfuse\Blaze\Support;

use Hashids\Hashids;
use Hashids\HashidsInterface;

class HashId
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
    public static function generate(...$arguments): string
    {
        return static::createHasher()->encode($arguments);
    }

    /**
     * Create Hashids instance.
     *
     * @param string|null $key
     *
     * @return \Hashids\HashidsInterface
     */
    public static function createHasher(?string $key = null): HashidsInterface
    {
        return new Hashids(
            $key ?: config('app.key', $key),
            self::CHARACTER_LENGTH,
            static::$characterPool
        );
    }
}
