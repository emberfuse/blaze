<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;

class RequestPasswordResetLinkTest extends TestCase
{
    use RefreshDatabase;

    public function testResetPasswordLinkRequestScreenCanBeRendered()
    {
        $response = $this->get('/forgot-password');

        $response->assertStatus(200);
    }

    public function testUsersCanRequestPasswordResetLink()
    {
        Notification::fake();

        $user = create(User::class);

        $response = $this->withoutExceptionHandling()
            ->from('/forgot-password')
            ->post('/forgot-password', [
                'email' => $user->email,
            ]);

        Notification::assertSentTo([$user], ResetPasswordNotification::class);

        $response->assertStatus(303);
        $response->assertRedirect('/forgot-password');
    }

    public function testUsersCanRequestPasswordResetLinkThroughXhrRequest()
    {
        Notification::fake();

        $user = create(User::class);

        $response = $this->withoutExceptionHandling()
            ->from('/forgot-password')
            ->postJson('/forgot-password', [
                'email' => $user->email,
            ]);

        Notification::assertSentTo([$user], ResetPasswordNotification::class);

        $response->assertStatus(200);
    }

    public function testResetLinkRequestCanFail()
    {
        $response = $this->from('/forgot-password')
            ->post('/forgot-password', [
                'email' => 'non.existant@user.com',
            ]);

        $response->assertStatus(403);
    }

    public function testResetLinkRequestCanFailWithXhr()
    {
        $response = $this->from('/forgot-password')
            ->postJson('/forgot-password', [
                'email' => 'non.existant@user.com',
            ]);

        $response->assertStatus(403);
    }

    public function testResetLinkCanBeSuccessfullyRequestedWithCustomizedEmailField()
    {
        config()->set('auth.credentials.email', 'email');

        create(User::class, [
            'email' => 'james@theredengine.com',
        ]);

        $response = $this->from('/forgot-password')
            ->post('/forgot-password', [
                'email' => 'james@theredengine.com',
            ]);

        $response->assertStatus(303);
        $response->assertRedirect('/forgot-password');
        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('status', trans(Password::RESET_LINK_SENT));
    }
}
