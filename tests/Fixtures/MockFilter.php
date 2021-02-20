<?php

namespace Cratespace\Preflight\Tests\Fixtures;

use Cratespace\Preflight\Filters\Filter;

class MockFilter extends Filter
{
    /**
     * Attributes to filters from.
     *
     * @var array
     */
    protected $filters = ['foo'];

    /**
     * Filter by bar.
     *
     * @return string
     */
    public function foo()
    {
        return 'bar';
    }
}
