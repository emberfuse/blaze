<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateUserPasswordTest extends TestCase
{
    use RefreshDatabase;

    public function testRequestValidatorUsesCustomErrorBag()
    {
        $user = create(User::class, [
            'password' => Hash::make('bustedCamel!'),
        ]);

        $response = $this->actingAs($user)
            ->put('/user/password', [
                'current_passsword' => 'bustedAndBrokenCamel',
                'password' => 'fixedCamel',
                'password_confirmation' => 'fixedCamel',
            ]);

        $response->assertStatus(302);
        $this->assertEquals(
            session()->get('errors')
                ->getBag('updatePassword')
                ->get('current_password'),
            [
                'The current password field is required.',
                'The provided password does not match your current password.',
            ]
        );
    }
}
