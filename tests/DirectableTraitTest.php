<?php

namespace Cratespace\Preflight;

use Illuminate\Support\Facades\Route;
use Cratespace\Preflight\Tests\TestCase;
use Cratespace\Preflight\Tests\Fixtures\MockModel;

class DirectableTraitTest extends TestCase
{
    public function testGetDefaultResourceRoute()
    {
        $model = new MockModel();

        Route::get('/mock/route', fn () => 'Hello')->name('mockmodels.show');

        $this->assertEquals('http://localhost/mock/route', $model->path);
    }

    public function testGetSetIndexResourceRoute()
    {
        $model = new MockModel();
        $model->index = 'model';

        Route::get('/mock/route', fn () => 'Hello')->name('model.show');

        $this->assertEquals('http://localhost/mock/route', $model->path);
    }
}
