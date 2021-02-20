<?php

namespace Cratespace\Preflight\Tests;

use Error;
use Cratespace\Preflight\Tests\Fixtures\MockModel;

class PresentableTraitTest extends TestCase
{
    public function testModelCanGetMutatedModelAttribute()
    {
        $this->expectException(Error::class);

        $model = new MockModel();
        $model->foo = 'bar';
        $model->present()->foobar;
    }
}
