<?php

namespace Cratespace\Preflight\Presenters;

use InvalidArgumentException;
use Cratespace\Preflight\Support\AbstractEloquent;

class Presenter extends AbstractEloquent
{
    /**
     * Show method as property if property does not exist.
     *
     * @param string $property
     *
     * @return mixed
     *
     * @throws \InvalidArgumentException
     */
    public function __get($property)
    {
        if (method_exists($this, $property)) {
            return call_user_func([$this, $property]);
        }

        return $this->handleExceptionThrow($property);
    }

    /**
     * Throw exception if property or method does not exist in object.
     *
     * @param string $property
     *
     * @return void
     *
     * @throws \InvalidArgumentException
     */
    protected function handleExceptionThrow(string $property): void
    {
        throw new InvalidArgumentException(sprintf('%s does not respond to the property or method "%s"', static::class, $property));
    }
}
