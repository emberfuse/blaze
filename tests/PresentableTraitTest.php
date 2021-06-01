<?php

namespace Emberfuse\Blaze\Tests;

use Error;
use Emberfuse\Blaze\Tests\Fixtures\MockModel;

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
