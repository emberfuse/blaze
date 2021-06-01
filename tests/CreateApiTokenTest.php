<?php

namespace Emberfuse\Blaze\Tests;

use Illuminate\Support\Facades\Hash;
use App\Actions\API\CreateNewApiToken;
use Emberfuse\Blaze\API\Permission;
use Emberfuse\Blaze\Tests\Fixtures\User;
use Emberfuse\Blaze\Contracts\Actions\CreatesNewApiTokens;

class CreateApiTokenTest extends TestCase
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

    public function testApiTokensCanBeCreated()
    {
        $this->withoutExceptionHandling();

        $this->migrate();

        $this->app->singleton(CreatesNewApiTokens::class, CreateNewApiToken::class);

        $this->actingAs($user = User::forceCreate([
            'name' => 'Thavarshan Thayananthajothy',
            'username' => 'Thavarshan',
            'email' => 'thavarshan@emberfuse.biz',
            'password' => Hash::make('secret-password'),
        ]));

        $response = $this->post('/user/api-tokens', [
            'name' => 'Test Token',
            'permissions' => ['read', 'update'],
        ]);

        $this->assertCount(1, $user->fresh()->tokens);
        $this->assertEquals('Test Token', $user->fresh()->tokens->first()->name);
        $this->assertTrue($user->fresh()->tokens->first()->can('read'));
        $this->assertFalse($user->fresh()->tokens->first()->can('delete'));
        $response->assertStatus(303);
    }
}
