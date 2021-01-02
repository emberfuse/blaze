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

    public function testItCanBeInstantiated()
    {
        $creator = new CreateNewUser();

        $this->assertInstanceOf(CreatesNewUsers::class, $creator);
    }

    public function testCanCreateNewUsers()
    {
        $creator = new CreateNewUser();

        $user = $creator->create([
            'name' => 'Bernard Jackson',
            'email' => 'cheesey.sleezy@bum.com',
            'password' => 'JumpedUpMonster!',
        ]);

        $this->assertInstanceOf(User::class, $user);
    }

    public function testCanAssignAfterHooks()
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

    public function testCanAssignUsernameToNewUsers()
    {
        $creator = new CreateNewUser();

        $user = $creator->create([
            'name' => 'Bernard Jackson',
            'email' => 'cheesey.sleezy@bum.com',
            'password' => 'JumpedUpMonster!',
        ]);

        $this->assertEquals('Bernard', $user->fresh()->username);
    }
}
