<?php

namespace Emberfuse\Blaze\Tests\Fixtures;

use Emberfuse\Blaze\Filters\Filter;

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
