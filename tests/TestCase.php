<?php

namespace Tests;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use Concerns\InteractsWithProtectedQualities,
        Concerns\InteractsWithNetwork,
        Concerns\CreatesNewUser,
        CreatesApplication,
        WithFaker;
}
