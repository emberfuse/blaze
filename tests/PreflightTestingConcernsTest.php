<?php

namespace Emberfuse\Blaze;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Emberfuse\Blaze\Tests\TestCase;
use Emberfuse\Blaze\Tests\Fixtures\User;
use Illuminate\Http\Client\ConnectionException;
use Emberfuse\Blaze\Testing\Concerns\AuthenticatesUser;
use Emberfuse\Blaze\Testing\Concerns\InteractsWithNetwork;
use Emberfuse\Blaze\Testing\Concerns\InteractsWithProtectedQualities;

class BlazeTestingConcernsTest extends TestCase
{
    use AuthenticatesUser;
    use InteractsWithNetwork;
    use InteractsWithProtectedQualities;

    public function testDetermineNetworkConnectionStatus()
    {
        try {
            $response = Http::get('www.example.com');
        } catch (ConnectionException $e) {
            $this->markTestSkipped($e->getMessage());
        }

        if ($response->ok()) {
            $this->assertTrue($this->isConnected());
        } else {
            $this->assertFalse($this->isConnected());
        }
    }

    public function testAccessProtectedQualities()
    {
        $pstub = new ProtectedQualitiesStub();

        $this->assertEquals('foo', $this->accessProperty($pstub, 'prop'));
        $this->accessMethod($pstub, 'setProp', ['bar']);
        $this->assertEquals('bar', $this->accessProperty($pstub, 'prop'));
    }

    public function testCreateNewFakeUser()
    {
        $this->migrate();

        $this->signIn($user = User::create([
            'name' => 'Thavarshan Thayananthajothy',
            'username' => 'Thavarshan',
            'email' => 'thavarshan@emberfuse.biz',
            'password' => Hash::make('secret-password'),
        ]));

        $this->assertTrue(Auth::check());
        $this->assertTrue(Auth::user()->is($user));
    }
}

class ProtectedQualitiesStub
{
    protected $prop = 'foo';

    protected function setProp(string $prop): void
    {
        $this->prop = $prop;
    }
}
