<?php

namespace Cratespace\Preflight\Tests;

use Cratespace\Preflight\Tests\Fixtures\MockModel;

class PresentableTraitTest extends TestCase
{
    public function testModelCanGetMutatedModelAttribute()
    {
        $this->expectErrorMessage("Class \"App\Presenters\MockModelPresenter\" not found");

        $model = new MockModel();
        $model->foo = 'bar';
        $model->present()->foobar;
    }
}
