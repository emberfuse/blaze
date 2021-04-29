<?php

namespace Cratespace\Preflight;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Cratespace\Preflight\Tests\TestCase;
use Cratespace\Preflight\Tests\Fixtures\User;
use Cratespace\Preflight\Testing\Concerns\AuthenticatesUser;
use Cratespace\Preflight\Testing\Concerns\InteractsWithNetwork;
use Cratespace\Preflight\Testing\Concerns\InteractsWithProtectedQualities;

class PreflightTestingConcernsTest extends TestCase
{
    use AuthenticatesUser;
    use InteractsWithNetwork;
    use InteractsWithProtectedQualities;

    public function testDetermineNetworkConnectionStatus()
    {
        $response = Http::get('www.example.com');

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
            'email' => 'thavarshan@cratespace.biz',
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
