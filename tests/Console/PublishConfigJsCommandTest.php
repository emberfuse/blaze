<?php

namespace Tests\Console;

use Tests\TestCase;

class PublishConfigJsCommandTest extends TestCase
{
    public function test_it_published_a_json_file_with_all_application_configurations()
    {
        $this->artisan('configjs:publish')
            ->expectsOutput('Config items published to json file [items.json].');

        $this->assertTrue(file_exists(resource_path('js/Config/items.json')));
    }
}
