<?php

namespace Emberfuse\Blaze\Tests\Fixtures;

use Emberfuse\Blaze\Presenters\Presenter;

class MockPresenter extends Presenter
{
    public function foobar(): string
    {
        return ucfirst($this->model->foo);
    }
}
