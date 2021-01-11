<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ConfirmablePasswordTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Instance of fake user.
     *
     * @var \App\Models\User;
     */
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = create(User::class, ['password' => 'BoomBoxAndBlasters']);
    }

    public function test_password_can_be_confirmed()
    {
        $response = $this->withoutExceptionHandling()
            ->actingAs($this->user)
            ->withSession(['url.intended' => 'http://foo.com/bar'])
            ->post('/user/confirm-password', ['password' => 'BoomBoxAndBlasters']);

        $response->assertSessionHas('auth.password_confirmed_at');
        $response->assertRedirect('http://foo.com/bar');
    }
}
