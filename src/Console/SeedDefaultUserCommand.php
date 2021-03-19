<?php

namespace Cratespace\Preflight\Console;

use Throwable;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Console\Command;

class SeedDefaultUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'preflight:user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create or seed default user account.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if ($this->confirm('Do you want to create a default user from preset data?', false)) {
            $credentials = config('defaults.users.credentials');
        } else {
            $credentials = $this->requestUserCredentials();
        }

        try {
            User::create($credentials);
        } catch (Throwable $e) {
            throw $e;
            $this->error($e->getMessage());
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
            'name' => $this->ask('Full name'),
            'username' => $this->ask('Username'),
            'email' => $this->ask('Email address'),
            'password' => $this->ask('Password'),
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
        ];
    }
}
