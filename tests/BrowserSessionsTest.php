<?php

namespace Cratespace\Preflight\Tests;

use Illuminate\Support\Facades\Hash;
use Cratespace\Preflight\Tests\Fixtures\User;

class BrowserSessionsTest extends TestCase
{
    public function testOtherBrowserSessionsCanBeLoggedOut()
    {
        $this->migrate();

        $this->actingAs($user = User::create([
            'name' => 'Thavarshan Thayananthajothy',
            'email' => 'thavarshan@cratespace.biz',
            'password' => Hash::make('secret-password'),
        ]));

        $response = $this->delete('/user/other-browser-sessions', [
            'password' => 'secret-password',
        ]);

        $response->assertSessionHasNoErrors();
    }
}
