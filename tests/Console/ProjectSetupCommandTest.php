<?php

namespace Tests\Console;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProjectSetupCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_setup_project_for_development()
    {
        $this->artisan('project:setup')
            ->expectsOutput('>> Welcome to the Preflight automated installation process! <<')
            ->expectsQuestion('Database name', 'preflight')
            ->expectsQuestion('Database port', 3306)
            ->expectsQuestion('Database user', 'root')
            ->expectsQuestion('Database password (leave blank for no password)', '')
            ->expectsConfirmation('Do you want to migrate the database?', 'no')
            ->expectsOutput('>> The installation process has successfully completed. <<')
            ->assertExitCode(0);
    }
}
