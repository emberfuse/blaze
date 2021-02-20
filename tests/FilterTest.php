<?php

namespace Cratespace\Preflight\Tests;

use Illuminate\Http\Request;
use Cratespace\Preflight\Tests\Fixtures\MockFilter;

class FilterTest extends TestCase
{
    public function testCanGetRelevantFiltersFromTheHttpReqeustInstance()
    {
        $mockFilter = new MockFilter(Request::create('/?foo=1', 'GET'));

        $this->assertEquals(['foo' => 1], $mockFilter->getFilters());
    }
}
