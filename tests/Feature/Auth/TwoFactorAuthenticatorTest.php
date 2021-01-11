<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class TwoFactorAuthenticatorTest extends TestCase
{
    use RefreshDatabase;
    use WithoutMiddleware;

    /** @test */
    public function two_factor_authentication_can_be_enabled()
    {
        $user = create(User::class, [
            'email' => 'james@silverman.com',
            'password' => bcrypt('monster'),
        ]);

        $response = $this->withoutExceptionHandling()
            ->actingAs($user)
            ->postJson('/user/two-factor-authentication');

        $response->assertStatus(200);

        $user->fresh();

        $this->assertNotNull($user->two_factor_secret);
        $this->assertNotNull($user->two_factor_recovery_codes);
        $this->assertIsArray(json_decode(decrypt($user->two_factor_recovery_codes), true));
        $this->assertNotNull($user->twoFactorQrCodeSvg());
    }

    /** @test */
    public function two_factor_authentication_can_be_disabled()
    {
        $user = create(User::class, [
            'email' => 'james@silverman.com',
            'password' => bcrypt('monster'),
        ]);

        $response = $this->withoutExceptionHandling()
            ->actingAs($user)
            ->deleteJson('/user/two-factor-authentication');

        $response->assertStatus(200);

        $user->fresh();

        $this->assertNull($user->two_factor_secret);
        $this->assertNull($user->two_factor_recovery_codes);
    }
}
