<?php

namespace Cratespace\Preflight;

use Cratespace\Preflight\Tests\TestCase;

class ActionMakeCommandTest extends TestCase
{
    /**
     * Name of test action class.
     *
     * @var string
     */
    protected $testAction;

    /**
     * Name of action class.
     *
     * @var string
     */
    protected $name = 'MockAction';

    public function setUp(): void
    {
        parent::setUp();

        $this->testAction = app_path("Actions/{$this->name}.php");
    }

    public function tearDown(): void
    {
        parent::tearDown();

        if (file_exists($this->testAction)) {
            unlink($this->testAction);
        }
    }

    public function testCreateActionClass()
    {
        $this->artisan('make:action', ['name' => $this->name])->assertExitCode(0);

        $this->assertTrue(is_file($this->testAction));
    }
}
