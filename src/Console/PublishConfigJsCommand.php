<?php

namespace Cratespace\Preflight\Console;

use Illuminate\Console\Command;

class PublishConfigJsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'configjs:publish';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish application configuration to json file for usage with JavaScript.';

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
        $configItems = resource_path('js/Config/items.json');

        if (! file_exists($configItems)) {
            @touch($configItems);
        }

        @file_put_contents($configItems, json_encode(config()->all()));
    }
}
