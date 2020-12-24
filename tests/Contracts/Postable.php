<?php

namespace Tests\Contracts;

interface Postable
{
    /**
     * Array of all valid parameters.
     *
     * @param array $override
     *
     * @return array
     */
    public function validParameters(array $overrides = []): array;
}
