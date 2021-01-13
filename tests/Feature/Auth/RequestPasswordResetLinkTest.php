<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Models\User;
use Tests\Contracts\Postable;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;

class RequestPasswordResetLinkTest extends TestCase implements Postable
{
    use RefreshDatabase;

    public function test_reset_password_link_request_screen_can_be_rendered()
    {
        $response = $this->get('/forgot-password');

        $response->assertStatus(200);
    }

    public function test_users_can_request_password_reset_link()
    {
        Notification::fake();

        $user = create(User::class);

        $response = $this->withoutExceptionHandling()
            ->from('/forgot-password')
            ->post('/forgot-password', $this->validParameters([
                'email' => $user->email,
            ]));

        Notification::assertSentTo([$user], ResetPasswordNotification::class);

        $response->assertStatus(303);
        $response->assertRedirect('/forgot-password');
    }

    public function test_users_can_request_password_reset_link_through_xhr_request()
    {
        Notification::fake();

        $user = create(User::class);

        $response = $this->withoutExceptionHandling()
            ->from('/forgot-password')
            ->postJson('/forgot-password', $this->validParameters([
                'email' => $user->email,
            ]));

        Notification::assertSentTo([$user], ResetPasswordNotification::class);

        $response->assertStatus(200);
    }

    public function test_reset_link_request_can_fail()
    {
        $response = $this->from('/forgot-password')
            ->post('/forgot-password', $this->validParameters());

        $response->assertStatus(403);
    }

    public function test_reset_link_request_can_fail_with_xhr()
    {
        $response = $this->from('/forgot-password')
            ->postJson('/forgot-password', $this->validParameters());

        $response->assertStatus(403);
    }

    public function test_reset_link_can_be_successfully_requested_with_customized_email_field()
    {
        config()->set('auth.credentials.email', 'email');

        create(User::class, [
            'email' => 'james@theredengine.com',
        ]);

        $response = $this->from('/forgot-password')
            ->post('/forgot-password', $this->validParameters([
                'email' => 'james@theredengine.com',
            ]));

        $response->assertStatus(303);
        $response->assertRedirect('/forgot-password');
        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('status', trans(Password::RESET_LINK_SENT));
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
        ], $overrides);
    }
}
