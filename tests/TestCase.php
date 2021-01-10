<?php

namespace Tests;

use Tests\Concerns\CreatesNewUser;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use CreatesNewUser;
    use WithFaker;
}
