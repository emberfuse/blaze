<?php

namespace Cratespace\Preflight\Testing\Contracts;

interface Postable
{
    /**
     * Provide only the necessary paramertes for a POST-able type request.
     *
     * @param array $overrides
     *
     * @return array
     */
    public function validParameters(array $overrides = []): array;
}
