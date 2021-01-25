<?php

namespace Cratespace\Preflight\Tests;

use Illuminate\Support\Facades\Hash;
use Cratespace\Preflight\Tests\Fixtures\User;

class CreateApiTokenTest extends TestCase
{
    public function testApiTokensCanBeCreated()
    {
        $this->migrate();

        $this->actingAs($user = User::forceCreate([
            'name' => 'Thavarshan Thayananthajothy',
            'username' => 'Thavarshan',
            'email' => 'tjthavarshan@gmail.com',
            'password' => Hash::make('secret-password'),
        ]));

        $response = $this->post('/user/api-tokens', [
            'name' => 'Test Token',
            'permissions' => [
                'read',
                'update',
            ],
        ]);

        $this->assertCount(1, $user->fresh()->tokens);
        $this->assertEquals('Test Token', $user->fresh()->tokens->first()->name);
        $this->assertTrue($user->fresh()->tokens->first()->can('read'));
        $this->assertFalse($user->fresh()->tokens->first()->can('delete'));
    }
}
