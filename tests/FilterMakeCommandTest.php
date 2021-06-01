<?php

namespace Emberfuse\Blaze\Tests;

class FilterMakeCommandTest extends TestCase
{
    /**
     * Name of test filter class.
     *
     * @var string
     */
    protected $testFilter;

    /**
     * Name of filter class.
     *
     * @var string
     */
    protected $name = 'MockFilter';

    public function setUp(): void
    {
        parent::setUp();

        $this->testFilter = app_path("Filters/{$this->name}.php");

        $this->deleteFile($this->testFilter);
    }

    public function tearDown(): void
    {
        parent::tearDown();

        $this->deleteFile($this->testFilter);
    }

    public function testCreateFilterClass()
    {
        $this->artisan('make:filter', ['name' => $this->name])->assertExitCode(0);

        $this->assertTrue(is_file($this->testFilter));
    }
}
