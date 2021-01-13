<?php

namespace Tests\Unit\Auth;

use stdClass;
use Throwable;
use TypeError;
use Tests\TestCase;
use App\Models\User;
use App\Auth\Actions\DeleteUser;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_delete_authenticatable_user_only()
    {
        $user = create(User::class);
        $invalidUser = new stdClass();
        $deletor = $this->app->make(DeleteUser::class);

        try {
            $deletor->delete($invalidUser);
        } catch (Throwable $e) {
            $this->assertInstanceOf(TypeError::class, $e);
        }

        $deletor->delete($user);

        $this->assertNull($user->fresh());
    }

    public function test_can_specify_action_to_delete_resources_associated_with_user()
    {
        $user = create(User::class);
        $deletor = $this->app->make(DeleteUser::class);

        $deletor::duringUserResourcesConcern(function ($user) {
            $this->assertInstanceOf(User::class, $user);
        });

        $deletor->delete($user);

        $this->assertNull($user->fresh());
    }
}
