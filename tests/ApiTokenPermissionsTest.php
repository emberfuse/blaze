<?php

namespace Cratespace\Preflight\Tests;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Cratespace\Preflight\API\Permission;
use Cratespace\Preflight\Tests\Fixtures\User;

class ApiTokenPermissionsTest extends TestCase
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

    public function testApiTokenPermissionsCanBeUpdated()
    {
        $this->migrate();

        $this->actingAs($user = User::create([
            'name' => 'Thavarshan Thayananthajothy',
            'email' => 'thavarshan@cratespace.biz',
            'password' => Hash::make('secret-password'),
        ]));

        $token = $user->tokens()->create([
            'name' => 'Test Token',
            'token' => Str::random(40),
            'abilities' => ['create', 'read'],
        ]);

        $response = $this->put('/user/api-tokens/' . $token->id, [
            'name' => $token->name,
            'permissions' => [
                'delete',
                'missing-permission',
            ],
        ]);

        $this->assertTrue($user->fresh()->tokens->first()->can('delete'));
        $this->assertFalse($user->fresh()->tokens->first()->can('read'));
        $this->assertFalse($user->fresh()->tokens->first()->can('missing-permission'));
    }
}
