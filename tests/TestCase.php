<?php

namespace Tests;

use Tests\Concerns\CreatesNewUser;
use Tests\Concerns\InteractsWithNetwork;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Concerns\InteractsWithProtectedQualities;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use InteractsWithProtectedQualities;
    use InteractsWithNetwork;
    use CreatesNewUser;
    use WithFaker;
}
