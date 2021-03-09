<?php

namespace Cratespace\Preflight\Tests\Fixtures;

use Cratespace\Preflight\Presenters\Presenter;

class MockPresenter extends Presenter
{
    public function foobar(): string
    {
        return ucfirst($this->model->foo);
    }
}
