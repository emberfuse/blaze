<?php

namespace Emberfuse\Blaze\Tests;

class PresenterMakeCommandTest extends TestCase
{
    /**
     * Name of test presenter class.
     *
     * @var string
     */
    protected $testPresenter;

    /**
     * Name of presenter class.
     *
     * @var string
     */
    protected $name = 'MockPresenter';

    public function setUp(): void
    {
        parent::setUp();

        $this->testPresenter = app_path("Presenters/{$this->name}.php");

        $this->deleteFile($this->testPresenter);
    }

    public function tearDown(): void
    {
        parent::tearDown();

        $this->deleteFile($this->testPresenter);
    }

    public function testCreatePresenterClass()
    {
        $this->artisan('make:presenter', ['name' => $this->name])->assertExitCode(0);

        $this->assertTrue(is_file($this->testPresenter));
    }
}
