<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OtherBrowserSessionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_other_browser_sessions_can_be_logged_out()
    {
        $this->withoutExceptionHandling()
            ->actingAs($user = create(User::class, [
                'password' => Hash::make('TopSecretPassword'),
            ]));

        $response = $this->delete('/user/other-browser-sessions', [
            'password' => 'TopSecretPassword',
        ]);

        $response->assertSessionDoesntHaveErrors(['password'], null, 'logoutOtherBrowsers');
    }
}
