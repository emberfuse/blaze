<?php

namespace Emberfuse\Blaze\Tests;

use Mockery as m;
use Emberfuse\Blaze\Tests\Fixtures\User;
use Orchestra\Testbench\TestCase as BaseTestCase;
use Emberfuse\Scorch\Providers\ScorchServiceProvider;
use Emberfuse\Blaze\Providers\BlazeServiceProvider;

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
            BlazeServiceProvider::class,
            ScorchServiceProvider::class,
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

    /**
     * Check for given files' existance and delete if found.
     *
     * @param string $file
     *
     * @return void
     */
    protected function deleteFile(string $file): void
    {
        if (file_exists($file)) {
            unlink($file);
        }
    }
}
