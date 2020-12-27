<?php

namespace Tests;

use Tests\Concerns\HasMacros;
use Tests\Concerns\CreatesFakeUser;
use Tests\Concerns\ChecksForInternet;
use Illuminate\Support\Facades\Schema;
use Tests\Concerns\TestsProtectedQualities;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use RefreshDatabase;
    use WithFaker;
    use ChecksForInternet;
    use CreatesFakeUser;
    use HasMacros;
    use TestsProtectedQualities;

    /**
     * Setup test suite.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        Schema::enableForeignKeyConstraints();

        $this->registerMacros();
    }
}
