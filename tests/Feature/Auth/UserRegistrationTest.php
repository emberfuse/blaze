<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function testUserCanAccessRegistrationPage()
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }
}
