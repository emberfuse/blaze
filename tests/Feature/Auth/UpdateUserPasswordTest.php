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

    public function testUsersCanUpdatePassword()
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

    public function testRequestValidatorUsesCustomErrorBag()
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

    public function testUsersCanNotResetPasswordWithInvalidCurrentPassword()
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

    public function testUsersCanNotResetPasswordWithInvalidPassword()
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

    public function testUsersCanNotResetPasswordWithInvalidPasswordConfirmation()
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
