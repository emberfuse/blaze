<?php

namespace Tests\Feature;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Cratespace\Preflight\API\Permission;
use Cratespace\Preflight\Tests\TestCase;
use Cratespace\Preflight\Tests\Fixtures\User;

class DeleteApiTokenTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Permission::permissions([
            'create',
            'read',
            'update',
            'delete',
        ]);
    }

    public function testApiTokensCanBeDeleted()
    {
        $this->migrate();

        $this->actingAs($user = User::forceCreate([
            'name' => 'Thavarshan Thayananthajothy',
            'username' => 'Thavarshan',
            'email' => 'thavarshan@cratespace.biz',
            'password' => Hash::make('secret-password'),
        ]));

        $token = $user->tokens()->create([
            'name' => 'Test Token',
            'token' => Str::random(40),
            'abilities' => ['create', 'read'],
        ]);

        $response = $this->delete('/user/api-tokens/' . $token->id);

        $this->assertCount(0, $user->fresh()->tokens);
    }
}
