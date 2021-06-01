<?php

namespace Emberfuse\Blaze\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class PublishConfigJsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'blaze:configjs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish application configuration to json file for usage with JavaScript.';

    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * Path to where config 'items' file should be located.
     *
     * @var string
     */
    protected $configPath;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
        $this->configPath = resource_path('js/Config');
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->publishConfigJs();

        $this->info('Config items published to json file [items.json].');

        return 0;
    }

    /**
     * Publish application configuration to json file for usage with JavaScript.
     *
     * @return void
     */
    protected function publishConfigJs(): void
    {
        if (! $this->files->isDirectory($this->configPath)) {
            $this->files->makeDirectory($this->configPath, 0777, true);
        }

        $configItems = $this->configPath . \DIRECTORY_SEPARATOR . 'items.json';

        if (! $this->files->exists($configItems)) {
            \touch($configItems);
        }

        $this->files->put($configItems, json_encode(config()->all()));
    }
}
