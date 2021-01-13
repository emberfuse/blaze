<?php

namespace App\Console\Commands;

use Throwable;
use Illuminate\Console\Command;
use Symfony\Component\Console\Question\Question;

class ProjectSetupCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'project:setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup project for development.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->welcome();

        $this->createEnvFile();

        if (0 === strlen(config('app.key'))) {
            $this->call('key:generate');

            $this->line('~ Secret key properly generated.');
        }

        $credentials = $this->requestDatabaseCredentials();

        $this->updateEnvironmentFile($credentials);

        $this->migrateDatabase($credentials);

        $this->call('storage:link');

        $this->call('cache:clear');

        $this->goodbye();

        return 0;
    }

    /**
     * Update the .env file from an array of $key => $value pairs.
     *
     * @param array $updatedValues
     * @return void
     */
    protected function updateEnvironmentFile(array $updatedValues): void
    {
        $envFile = $this->laravel->environmentFilePath();

        foreach ($updatedValues as $key => $value) {
            @file_put_contents($envFile, preg_replace(
                "/{$key}=(.*)/",
                "{$key}={$value}",
                @file_get_contents($envFile)
            ));
        }
    }

    /**
     * Migrate application database.
     *
     * @param array $credentials
     * @return void
     */
    protected function migrateDatabase(array $credentials): void
    {
        if ($this->confirm('Do you want to migrate the database?', false)) {
            $this->migrateDatabaseWithFreshCredentials($credentials);

            $this->line('~ Database successfully migrated.');
        }
    }

    /**
     * Display the welcome message.
     *
     * @return void
     */
    protected function welcome(): void
    {
        $this->info('>> Welcome to the Preflight automated installation process! <<');
    }

    /**
     * Display the completion message.
     *
     * @return void
     */
    protected function goodbye(): void
    {
        $this->info('>> The installation process has successfully completed. <<');
    }

    /**
     * Request the local database details from the user.
     *
     * @return array
     */
    protected function requestDatabaseCredentials(): array
    {
        return [
            'DB_DATABASE' => $this->ask('Database name'),
            'DB_PORT' => $this->ask('Database port', 3306),
            'DB_USERNAME' => $this->ask('Database user'),
            'DB_PASSWORD' => $this->askHiddenWithDefault('Database password (leave blank for no password)'),
        ];
    }

    /**
     * Create the initial .env file.
     *
     * @return void
     */
    protected function createEnvFile(): void
    {
        $envFile = $this->laravel->environmentFilePath();

        if (! @file_exists($envFile)) {
            try {
                @copy('.env.example', $envFile);
            } catch (Throwable $e) {
                @touch($envFile);
            }

            $this->line("'.env' file successfully created");
        }
    }

    /**
     * Migrate the db with the new credentials.
     *
     * @param array $credentials
     *
     * @return void
     */
    protected function migrateDatabaseWithFreshCredentials(array $credentials): void
    {
        $seed = $this->confirm('Do you want to seed the database with default user and dummy data?', false);

        foreach ($credentials as $key => $value) {
            $configKey = strtolower(str_replace('DB_', '', $key));

            if ('password' === $configKey && 'null' == $value) {
                config(["database.connections.mysql.{$configKey}" => '']);

                continue;
            }

            config(["database.connections.mysql.{$configKey}" => $value]);
        }

        $seed ? $this->call('migrate:fresh', ['--seed' => 'default']) : $this->call('migrate:fresh');
    }

    /**
     * Prompt the user for optional input but hide the answer from the console.
     *
     * @param string $question
     * @param bool   $fallback
     *
     * @return string
     */
    public function askHiddenWithDefault(string $question, bool $fallback = true): string
    {
        $question = new Question($question, 'null');

        $question->setHidden(true)->setHiddenFallback($fallback);

        return $this->output->askQuestion($question);
    }
}
