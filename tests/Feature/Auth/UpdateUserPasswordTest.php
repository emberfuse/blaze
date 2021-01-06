<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateUserPasswordTest extends TestCase
{
    use RefreshDatabase;

    public function testUsersCanUpdatePassword()
    {
        $user = create(User::class, [
            'password' => Hash::make('bustedCamel!'),
        ]);

        $response = $this->withExceptionHandling()
            ->actingAs($user)
            ->put('/user/password', [
                'current_password' => 'bustedCamel!',
                'password' => 'fixedCamel',
                'password_confirmation' => 'fixedCamel',
            ]);

        $response->assertStatus(200);
    }

    public function testRequestValidatorUsesCustomErrorBag()
    {
        $user = create(User::class, [
            'password' => Hash::make('bustedCamel!'),
        ]);

        $response = $this->actingAs($user)
            ->put('/user/password', [
                'current_password' => 'bustedAndBrokenCamel',
                'password' => 'fixedCamel',
                'password_confirmation' => 'fixedCamel',
            ]);

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
            ->put('/user/password', [
                'current_password' => 'bustedAndBrokenCamel',
                'password' => 'fixedCamel',
                'password_confirmation' => 'fixedCamel',
            ]);

        $response->assertSessionHasErrorsIn('updatePassword', 'current_password');
    }

    public function testUsersCanNotResetPasswordWithInvalidPassword()
    {
        $user = create(User::class, [
            'password' => Hash::make('bustedCamel!'),
        ]);

        $response = $this->actingAs($user)
            ->put('/user/password', [
                'current_password' => 'bustedCamel',
                'password' => '',
                'password_confirmation' => 'fixedCamel',
            ]);

        $response->assertSessionHasErrorsIn('updatePassword', 'password');
    }

    public function testUsersCanNotResetPasswordWithInvalidPasswordConfirmation()
    {
        $user = create(User::class, [
            'password' => Hash::make('bustedCamel!'),
        ]);

        $response = $this->actingAs($user)
            ->put('/user/password', [
                'current_password' => 'bustedCamel',
                'password' => 'fixedCamel',
                'password_confirmation' => '',
            ]);

        $response->assertSessionHasErrorsIn('updatePassword', 'password');
    }
}
