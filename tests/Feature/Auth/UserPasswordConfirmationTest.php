<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserPasswordConfirmationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test user instance.
     *
     * @var \Illuminate\Contracts\Auth\Authenticatable
     */
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = create(User::class, [
            'name' => 'Fred Fred Burger',
            'email' => 'fred.f@burger.com',
            'password' => Hash::make('MyNameIsFredFredBurgerYes!'),
        ]);
    }

    public function test_password_can_be_confirmed()
    {
        $response = $this->withoutExceptionHandling()
            ->actingAs($this->user)
            ->withSession(['url.intended' => 'http://foo.com/bar'])
            ->post('/user/confirm-password', ['password' => 'MyNameIsFredFredBurgerYes!']);

        $response->assertSessionHas('auth.password_confirmed_at');
        $response->assertRedirect('http://foo.com/bar');
    }

    public function test_password_confirmation_can_fail_with_an_invalid_password()
    {
        $response = $this->actingAs($this->user)
            ->withSession(['url.intended' => 'http://foo.com/bar'])
            ->post('/user/confirm-password', ['password' => 'invalid']);

        $response->assertSessionHasErrors(['password']);
        $response->assertSessionMissing('auth.password_confirmed_at');
        $response->assertRedirect();
        $this->assertNotEquals($response->getTargetUrl(), 'http://foo.com/bar');
    }

    public function test_password_confirmation_can_fail_without_apassword()
    {
        $response = $this->actingAs($this->user)
            ->withSession(['url.intended' => 'http://foo.com/bar'])
            ->post('/user/confirm-password', ['password' => null]);

        $response->assertSessionHasErrors(['password']);
        $response->assertSessionMissing('auth.password_confirmed_at');
        $response->assertRedirect();
        $this->assertNotEquals($response->getTargetUrl(), 'http://foo.com/bar');
    }

    public function test_password_can_be_confirmed_with_json()
    {
        $response = $this->actingAs($this->user)
            ->postJson('/user/confirm-password', ['password' => 'MyNameIsFredFredBurgerYes!']);

        $response->assertStatus(201);
    }

    public function test_password_confirmation_can_fail_with_json()
    {
        $response = $this->actingAs($this->user)
            ->postJson('/user/confirm-password', ['password' => 'invalid']);

        $response->assertJsonValidationErrors('password');
    }
}
