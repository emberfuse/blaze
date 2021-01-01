<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginControllerTest extends TestCase
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
            ->post('/login', [
                'email' => $user->email,
                'password' => 'CheesedUpCrackers!',
            ]);

        $response->assertStatus(303);
        $response->assertRedirect(RouteServiceProvider::HOME);
        $this->assertAuthenticated();
    }

    public function testUsersCanAuthenticateUsingXhrrequest()
    {
        $user = create(User::class, [
            'email' => 'cheesey@crackers.com',
            'password' => Hash::make('CheesedUpCrackers!'),
        ]);

        $this->assertDatabaseHas('users', ['email' => 'cheesey@crackers.com']);

        $response = $this->withoutExceptionHandling()
            ->from('/login')
            ->postJson('/login', [
                'email' => $user->email,
                'password' => 'CheesedUpCrackers!',
            ]);

        $response->assertStatus(200);
        $this->assertAuthenticated();
    }

    public function testUsersCanNotAuthenticateWithInvalidEmail()
    {
        $user = create(User::class, [
            'email' => 'cheesey@crackers.com',
            'password' => 'CheesedUpCrackers!',
        ]);

        $response = $this->post('/login', [
            'email' => 'plain@crackers.com',
            'password' => 'CheesedUpCrackers!',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('email');
    }

    public function testUsersCanNotAuthenticateWithInvalidPassword()
    {
        $user = create(User::class);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'PlainCrackers!',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('email');
    }
}
