<?php

namespace Tests\Unit\Auth;

use Tests\TestCase;
use App\Models\User;
use App\Auth\Actions\CreateNewUser;
use App\Contracts\Auth\CreatesNewUsers;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateNewUserTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        CreateNewUser::afterCreatingUser(null);
    }

    public function test_it_can_be_instantiated()
    {
        $creator = new CreateNewUser();

        $this->assertInstanceOf(CreatesNewUsers::class, $creator);
    }

    public function test_can_create_new_users()
    {
        $creator = new CreateNewUser();

        $user = $creator->create([
            'name' => 'Bernard Jackson',
            'email' => 'cheesey.sleezy@bum.com',
            'password' => 'JumpedUpMonster!',
        ]);

        $this->assertInstanceOf(User::class, $user);
    }

    public function test_can_assign_after_hooks()
    {
        $creator = new CreateNewUser();

        $attributes = [
            'name' => 'Bernard Jackson',
            'email' => 'cheesey.sleezy@bum.com',
            'password' => 'JumpedUpMonster!',
        ];

        CreateNewUser::afterCreatingUser(function ($user, $data) use ($attributes) {
            $this->assertInstanceOf(User::class, $user);
            $this->assertIsArray($data);
            $this->assertSame($data, $attributes);
        });

        $user = $creator->create($attributes);

        $this->assertInstanceOf(User::class, $user);
    }

    public function test_can_assign_username_to_new_users()
    {
        $creator = new CreateNewUser();

        $user = $creator->create([
            'name' => 'Bernard Jackson',
            'email' => 'cheesey.sleezy@bum.com',
            'password' => 'JumpedUpMonster!',
        ]);

        $this->assertEquals('BernardJackson', $user->fresh()->username);
    }
}
