<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function testUserCanAccessRegistrationPage()
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function testNewUsersCanRegister()
    {
        $response = $this->withoutExceptionHandling()
            ->from('/register')
            ->post('/register', [
                'name' => 'Bernard Jackson',
                'email' => 'cheesey.sleezy@bum.com',
                'password' => 'JumpedUpMonster!',
            ]);

        $this->assertAuthenticated();
        $response->assertStatus(303);
        $response->assertRedirect(RouteServiceProvider::HOME);
    }

    public function testNewUsersCanRegisterWithXhrRequest()
    {
        $response = $this->withoutExceptionHandling()
            ->from('/register')
            ->postJson('/register', [
                'name' => 'Bernard Jackson',
                'email' => 'cheesey.sleezy@bum.com',
                'password' => 'JumpedUpMonster!',
            ]);

        $this->assertAuthenticated();
        $response->assertStatus(201);
    }

    public function testUsersCanNotRegisterWithInvalidEmail()
    {
        $response = $this->post('/register', [
            'name' => 'Bernard Jackson',
            'email' => 'plaincrackers.com',
            'password' => 'CheesedUpCrackers!',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('email');
    }

    public function testUsersCanNotRegisterWithInvalidName()
    {
        $response = $this->post('/register', [
            'name' => '',
            'email' => 'plain@crackers.com',
            'password' => 'CheesedUpCrackers!',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('name');
    }

    public function testUsersCanNotRegisterWithInvalidPassword()
    {
        $response = $this->post('/register', [
            'name' => 'Bernard Jackson',
            'email' => 'plain@crackers.com',
            'password' => '!',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('password');
    }
}
