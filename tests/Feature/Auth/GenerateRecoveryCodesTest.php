<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use App\Events\RecoveryCodesGenerated;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class GenerateRecoveryCodesTest extends TestCase
{
    use RefreshDatabase;
    use WithoutMiddleware;

    public function test_new_recovery_codes_can_be_generated()
    {
        Event::fake();

        $user = create(User::class);

        $response = $this->withoutExceptionHandling()
            ->signIn($user)
            ->postJson('/user/two-factor-recovery-codes');

        $response->assertStatus(200);

        Event::assertDispatched(RecoveryCodesGenerated::class);

        $user->fresh();

        $this->assertNotNull($user->two_factor_recovery_codes);
        $this->assertIsArray(json_decode(decrypt($user->two_factor_recovery_codes), true));
    }
}
