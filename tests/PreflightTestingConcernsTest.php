<?php

namespace Cratespace\Preflight;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Cratespace\Preflight\Tests\TestCase;
use Cratespace\Preflight\Tests\Fixtures\User;
use Cratespace\Preflight\Testing\Concerns\CreatesNewUser;
use Cratespace\Preflight\Testing\Concerns\InteractsWithNetwork;
use Cratespace\Preflight\Testing\Concerns\InteractsWithProtectedQualities;

class PreflightTestingConcernsTest extends TestCase
{
    use CreatesNewUser;
    use InteractsWithNetwork;
    use InteractsWithProtectedQualities;

    public function testDetermineNetworkConnectionStatus()
    {
        $this->assertTrue($this->isConnected());
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
