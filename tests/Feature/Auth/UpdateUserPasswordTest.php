<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Models\User;
use Tests\Contracts\Postable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateUserPasswordTest extends TestCase implements Postable
{
    use RefreshDatabase;

    public function test_users_can_update_password()
    {
        $user = create(User::class, [
            'password' => Hash::make('bustedCamel!'),
        ]);

        $response = $this->withExceptionHandling()
            ->actingAs($user)
            ->put('/user/password', $this->validParameters([
                'current_password' => 'bustedCamel!',
            ]));

        $response->assertStatus(303);
    }

    public function test_request_validator_uses_custom_error_bag()
    {
        $user = create(User::class, [
            'password' => Hash::make('bustedCamel!'),
        ]);

        $response = $this->actingAs($user)
            ->put('/user/password', $this->validParameters([
                'current_password' => 'bustedAndBrokenCamel',
            ]));

        $response->assertStatus(302);

        $this->assertEquals(
            session()->get('errors')
                ->getBag('updatePassword')
                ->get('current_password'),
            ['The provided password does not match your current password.']
        );
    }

    public function test_users_can_not_reset_password_with_invalid_current_password()
    {
        $user = create(User::class, [
            'password' => Hash::make('bustedCamel!'),
        ]);

        $response = $this->actingAs($user)
            ->put('/user/password', $this->validParameters([
                'current_password' => 'bustedAndBrokenCamel',
            ]));

        $response->assertSessionHasErrorsIn('updatePassword', 'current_password');
    }

    public function test_users_can_not_reset_password_with_invalid_password()
    {
        $user = create(User::class, [
            'password' => Hash::make('bustedCamel!'),
        ]);

        $response = $this->actingAs($user)
            ->put('/user/password', $this->validParameters([
                'current_password' => 'bustedCamel',
                'password' => '',
            ]));

        $response->assertSessionHasErrorsIn('updatePassword', 'password');
    }

    public function test_users_can_not_reset_password_with_invalid_password_confirmation()
    {
        $user = create(User::class, [
            'password' => Hash::make('bustedCamel!'),
        ]);

        $response = $this->actingAs($user)
            ->put('/user/password', $this->validParameters([
                'current_password' => 'bustedCamel',
                'password_confirmation' => '',
            ]));

        $response->assertSessionHasErrorsIn('updatePassword', 'password');
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
        $password = uniqid();

        return array_merge([
            'current_password' => '',
            'password' => $password,
            'password_confirmation' => $password,
        ], $overrides);
    }
}
