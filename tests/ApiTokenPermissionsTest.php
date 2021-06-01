<?php

namespace Emberfuse\Blaze\Tests;

use App\Models\User;
use Illuminate\Support\Str;
use App\Actions\API\UpdateApiToken;
use Illuminate\Support\Facades\Hash;
use Emberfuse\Blaze\API\Permission;
use Emberfuse\Blaze\Contracts\Actions\UpdatesApiTokens;

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
        $this->withoutExceptionHandling();
        $this->migrate();

        $this->app->singleton(UpdatesApiTokens::class, UpdateApiToken::class);

        $this->actingAs($user = User::create([
            'name' => 'Thavarshan Thayananthajothy',
            'username' => 'Thavarshan',
            'email' => 'thavarshan@emberfuse.biz',
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
