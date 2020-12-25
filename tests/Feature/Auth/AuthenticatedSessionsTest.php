<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthenticatedSessionsTest extends TestCase
{
    protected function tearDown(): void
    {
        auth()->logout();
    }

    public function testUserCanLoginToTheirAccount()
    {
        $user = create(User::class, [
            'email' => 'cheesey@crackermail.com',
            'password' => Hash::make('cheesedUpCracker'),
        ]);

        $response = $this->withoutExceptionHandling()
            ->postJson('/login', [
                'email' => 'cheesey@crackermail.com',
                'password' => 'cheesedUpCracker',
            ]);

        $response->assertStatus(200);
        $this->assertTrue(auth()->user()->is($user));
    }
}
