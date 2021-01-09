<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateUserProfileInformationTest extends TestCase
{
    use RefreshDatabase;

    public function testUsersCanUpdateProfleInformation()
    {
        $user = create(User::class, [
            'name' => 'Mikey Mitchel',
        ]);

        $response = $this->withExceptionHandling()
            ->actingAs($user)
            ->put('/user/profile', [
                'name' => 'Fred Fred Burger',
                'username' => 'FrenchFriedFred',
                'email' => 'fred.fburger@underworld.com',
            ]);

        $response->assertStatus(303);
        $this->assertNotEquals('Mikey Mitchel', $user->fresh()->name);
    }
}
