<?php

namespace Cratespace\Preflight;

use Cratespace\Preflight\Tests\TestCase;

class PublishConfigJsCommandTest extends TestCase
{
    /**
     * JS config items file.
     *
     * @var string
     */
    protected $items;

    public function setUp(): void
    {
        parent::setUp();

        $this->items = resource_path('js/Config/items.json');

        $this->deleteFile($this->items);
    }

    public function testPublishConfigAsJsonFile()
    {
        $this->artisan('preflight:configjs')
            ->expectsOutput('Config items published to json file [items.json].')
            ->assertExitCode(0);

        $this->assertTrue(file_exists($this->items));
    }
}
