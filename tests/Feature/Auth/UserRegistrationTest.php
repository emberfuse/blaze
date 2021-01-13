<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_access_registration_page()
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register()
    {
        $response = $this->withoutExceptionHandling()
            ->from('/register')
            ->post('/register', $this->validParameters());

        $this->assertAuthenticated();
        $response->assertStatus(303);
        $response->assertRedirect(RouteServiceProvider::HOME);
    }

    public function test_new_users_can_register_with_xhr_request()
    {
        $response = $this->withoutExceptionHandling()
            ->from('/register')
            ->postJson('/register', $this->validParameters());

        $this->assertAuthenticated();
        $response->assertStatus(201);
    }

    public function test_fires_events_after_successful_registration_and_login()
    {
        Event::fake();

        $response = $this->withoutExceptionHandling()
            ->from('/register')
            ->post('/register', $this->validParameters());

        Event::assertDispatched(Registered::class);
        Event::assertDispatched(Login::class);

        $this->assertAuthenticated();
        $response->assertStatus(303);
        $response->assertRedirect(RouteServiceProvider::HOME);
    }

    public function test_users_can_not_register_with_invalid_email()
    {
        $response = $this->post('/register', $this->validParameters([
            'email' => 'plaincrackers.com',
        ]));

        $this->assertGuest();
        $response->assertSessionHasErrors('email');
    }

    public function test_users_can_not_register_with_invalid_name()
    {
        $response = $this->post('/register', $this->validParameters(['name' => '']));

        $this->assertGuest();
        $response->assertSessionHasErrors('name');
    }

    public function test_users_can_not_register_with_invalid_password()
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
