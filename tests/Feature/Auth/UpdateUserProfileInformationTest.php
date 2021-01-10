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
            ->put('/user/profile', $this->validParameters());

        $response->assertStatus(303);
        $this->assertNotEquals('Mikey Mitchel', $user->fresh()->name);
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
            'name' => $this->faker->name,
            'username' => $this->faker->userName,
            'email' => $this->faker->email,
        ], $overrides);
    }
}
