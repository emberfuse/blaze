<?php

namespace Emberfuse\Blaze\Testing\Concerns;

use ReflectionClass;

trait InteractsWithProtectedQualities
{
    /**
     * Grant access to protected/private class property.
     *
     * @param object $object
     * @param string $property
     *
     * @return mixed
     */
    protected function accessProperty(object $object, string $property)
    {
        $objectReflection = new ReflectionClass($object);
        $property = $objectReflection->getProperty($property);
        $property->setAccessible(true);

        return $property->getValue($object);
    }

    /**
     * Grant access to protected/private class methods.
     *
     * @param object  $object
     * @param string  $method
     * @param array[] $parameters
     *
     * @return mixed
     */
    protected function accessMethod(object $object, string $method, array $parameters = [])
    {
        $objectReflection = new ReflectionClass($object);
        $method = $objectReflection->getMethod($method);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }
}
