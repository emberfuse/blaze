<?php

namespace Cratespace\Preflight\Support;

use Hashids\Hashids;
use Hashids\HashidsInterface;

class HashId extends Uid
{
    /**
     * Generate a new and unique code.
     *
     * @param array[] $arguments
     *
     * @return string
     */
    public static function generate(...$arguments): string
    {
        return static::hasher()->encode($arguments);
    }

    /**
     * Get Hash ID generator instance.
     *
     * @param string|null $key
     *
     * @return \Hashids\HashidsInterface
     */
    protected static function hasher(?string $key = null): HashidsInterface
    {
        return new Hashids(
            $key ?? config('app.key', $key),
            self::CHARACTER_LENGTH,
            static::$characterPool
        );
    }
}
