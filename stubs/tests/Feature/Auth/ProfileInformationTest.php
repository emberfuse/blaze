<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProfileInformationTest extends TestCase
{
    use RefreshDatabase;

    public function testProfileInformationCanBeUpdated()
    {
        $this->signIn($user = create(User::class));

        $response = $this->put('/user/profile', [
            'name' => 'Test Name',
            'username' => 'TestUserName',
            'email' => 'test@example.com',
            'phone' => '0712345678',
        ]);

        $this->assertEquals('Test Name', $user->fresh()->name);
        $this->assertEquals('test@example.com', $user->fresh()->email);
    }
}
