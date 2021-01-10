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
            ->post('/register', $this->validParameters());

        $this->assertAuthenticated();
        $response->assertStatus(303);
        $response->assertRedirect(RouteServiceProvider::HOME);
    }

    public function testNewUsersCanRegisterWithXhrRequest()
    {
        $response = $this->withoutExceptionHandling()
            ->from('/register')
            ->postJson('/register', $this->validParameters());

        $this->assertAuthenticated();
        $response->assertStatus(201);
    }

    public function testUsersCanNotRegisterWithInvalidEmail()
    {
        $response = $this->post('/register', $this->validParameters([
            'email' => 'plaincrackers.com',
        ]));

        $this->assertGuest();
        $response->assertSessionHasErrors('email');
    }

    public function testUsersCanNotRegisterWithInvalidName()
    {
        $response = $this->post('/register', $this->validParameters(['name' => '']));

        $this->assertGuest();
        $response->assertSessionHasErrors('name');
    }

    public function testUsersCanNotRegisterWithInvalidPassword()
    {
        $response = $this->post('/register', $this->validParameters([
            'password' => '!',
        ]));

        $this->assertGuest();
        $response->assertSessionHasErrors('password');
    }

    /**
     * Provide only the necessary paramertes for a POST-able type request.
     *
     * @param array $overrides
     *
     * @return array
     */
    public function validParameters(array $overrides = []): array
    {
        return array_merge([
            'name' => $this->faker->name,
            'username' => $this->faker->userName,
            'email' => $this->faker->email,
            'password' => uniqid(),
        ], $overrides);
    }
}
