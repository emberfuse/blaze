<?php

namespace Emberfuse\Blaze\Tests;

use InvalidArgumentException;
use Emberfuse\Blaze\Tests\Fixtures\MockModel;
use Emberfuse\Blaze\Tests\Fixtures\MockPresenter;

class PresenterTest extends TestCase
{
    protected $model;
    protected $presenter;

    public function setUp(): void
    {
        parent::setUp();

        $this->model = new MockModel();
        $this->model->foo = 'bar';
        $this->presenter = new MockPresenter($this->model);
    }

    public function testCanPresentMutatedModelAttribute()
    {
        $this->assertEquals('Bar', $this->presenter->foobar);
    }

    public function testThrowsExceptionIfPresenterMethodUndefined()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Emberfuse\Blaze\Tests\Fixtures\MockPresenter does not respond to the property or method "barbaz"');

        $this->presenter->barbaz;
    }
}
