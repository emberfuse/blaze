<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Models\User;
use Tests\Contracts\Postable;
use Illuminate\Support\Facades\Hash;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthenticationTest extends TestCase implements Postable
{
    use RefreshDatabase;

    public function testLoginScreenCanBeRendered()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function testUsersCanAuthenticateUsingTheLoginScreen()
    {
        $user = create(User::class, [
            'email' => 'cheesey@crackers.com',
            'password' => Hash::make('CheesedUpCrackers!'),
        ]);

        $this->assertDatabaseHas('users', ['email' => 'cheesey@crackers.com']);

        $response = $this->withoutExceptionHandling()
            ->from('/login')
            ->post('/login', $this->validParameters([
                'email' => $user->email,
                'password' => 'CheesedUpCrackers!',
            ]));

        $response->assertStatus(303);
        $response->assertRedirect(RouteServiceProvider::HOME);
        $this->assertAuthenticated();
    }

    public function testUsersCanAuthenticateUsingXhrRequest()
    {
        $user = create(User::class, [
            'email' => 'cheesey@crackers.com',
            'password' => Hash::make('CheesedUpCrackers!'),
        ]);

        $this->assertDatabaseHas('users', ['email' => 'cheesey@crackers.com']);

        $response = $this->withoutExceptionHandling()
            ->from('/login')
            ->postJson('/login', $this->validParameters([
                'email' => $user->email,
                'password' => 'CheesedUpCrackers!',
            ]));

        $response->assertStatus(200);
        $this->assertAuthenticated();
    }

    public function testUsersCanNotAuthenticateWithInvalidEmail()
    {
        $user = create(User::class, [
            'email' => 'cheesey@crackers.com',
            'password' => 'CheesedUpCrackers!',
        ]);

        $response = $this->post('/login', $this->validParameters([
            'email' => '',
            'password' => 'CheesedUpCrackers!',
        ]));

        $this->assertGuest();
        $response->assertSessionHasErrors('email');
    }

    public function testUsersCanNotAuthenticateWithInvalidPassword()
    {
        $user = create(User::class);

        $response = $this->post('/login', $this->validParameters([
            'email' => $user->email,
            'password' => 'CheesedUpCrackers!',
        ]));

        $this->assertGuest();
        $response->assertSessionHasErrors('email');
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
            'email' => $this->faker->email,
            'password' => $this->faker->word(10),
        ], $overrides);
    }
}
