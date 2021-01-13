<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Models\User;
use App\Jobs\DeleteUserJob;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Queue;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_accounts_can_be_deleted()
    {
        Queue::fake();

        $this->signIn($user = create(User::class, [
            'password' => Hash::make('jumboJollyJet'),
        ]));

        $response = $this->delete('/user/profile', [
            'password' => 'jumboJollyJet',
        ]);

        Queue::assertPushed(
            fn (DeleteUserJob $job) => $job->getUser()->id === $user->id
        );
    }

    public function test_correct_password_must_be_provided_before_account_can_be_deleted()
    {
        Queue::fake();

        $response = $this->signIn($user = create(User::class))
            ->delete('/user/profile', [
                'password' => 'wrong-password',
            ]);

        Queue::assertNotPushed(DeleteUserJob::class);

        $this->assertNotNull($user->fresh());
    }
}
