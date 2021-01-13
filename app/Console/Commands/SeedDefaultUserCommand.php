<?php

namespace App\Console\Commands;

use Throwable;
use Illuminate\Support\Str;
use Illuminate\Console\Command;
use App\Contracts\Auth\CreatesNewUsers;

class SeedDefaultUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create or seed default user account.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(CreatesNewUsers $creator)
    {
        parent::__construct();

        $this->creator = $creator;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $credentials = null;

        if ($this->confirm('Do you want to create a default user from preset data?', false)) {
            $credentials = config('defaults.users.credentials');
        }

        if (is_null($credentials)) {
            $credentials = $this->requestUserCredentials();
        }

        try {
            $this->creator->create($credentials);
        } catch (Throwable $e) {
            $this->error($e->getMessage());

            return 0;
        }

        $this->line('Default user created.');

        return 0;
    }

    /**
     * Request the local database details from the user.
     */
    protected function requestUserCredentials(): array
    {
        return [
            'name' => $this->ask('User full name'),
            'email' => $this->ask('Email address'),
            'password' => $this->ask('Password'),
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
        ];
    }
}
