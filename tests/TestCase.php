<?php

namespace Cratespace\Preflight\Tests;

use Mockery as m;
use Cratespace\Preflight\Tests\Fixtures\User;
use Orchestra\Testbench\TestCase as BaseTestCase;
use Cratespace\Citadel\Providers\CitadelServiceProvider;
use Cratespace\Preflight\Providers\PreflightServiceProvider;

abstract class TestCase extends BaseTestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function tearDown(): void
    {
        m::close();
    }

    protected function getPackageProviders($app)
    {
        return [
            PreflightServiceProvider::class,
            CitadelServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['migrator']->path(__DIR__ . '/../database/migrations');

        $app['config']->set('auth.providers.users.model', User::class);

        $app['config']->set('database.default', 'testbench');

        $app['config']->set('database.connections.testbench', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }

    /**
     * Load migrations and create database.
     *
     * @return void
     */
    protected function migrate(): void
    {
        $this->loadLaravelMigrations(['--database' => 'testbench']);

        $this->artisan('migrate:fresh', ['--database' => 'testbench'])->run();
    }
}
