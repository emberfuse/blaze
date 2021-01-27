<?php

namespace Cratespace\Preflight;

use Cratespace\Preflight\Tests\TestCase;

class PublishConfigJsCommand extends TestCase
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

        if (file_exists($this->items)) {
            \unlink($this->items);
        }
    }

    public function testPublishConfigAsJsonFile()
    {
        $this->artisan('preflight:configjs')
            ->expectsOutput('Config items published to json file [items.json].')
            ->assertExitCode(0);

        $this->assertTrue(file_exists($this->items));
    }
}
