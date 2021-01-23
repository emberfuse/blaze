<?php

namespace App\Auth\Actions;

use Closure;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Contracts\Auth\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    /**
     * Callback that will be executed after a user has been created.
     *
     * @var \Closure|null
     */
    protected static $afterCreatingUser;

    /**
     * Create a newly registered user.
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            return tap($this->createUser($data), function (User $user) use ($data) {
                if (static::$afterCreatingUser) {
                    call_user_func_array(static::$afterCreatingUser, [$user, $data]);
                }

                return $user;
            });
        });
    }

    /**
     * Create new user profile.
     *
     * @param array $data
     * @return \App\Models\User
     */
    protected function createUser(array $data): User
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'username' => $this->makeUsername($data['name']),
            'password' => Hash::make($data['password']),
        ]);
    }

    /**
     * Generate unique username from first name.
     *
     * @param string $name
     * @return string
     */
    protected function makeUsername(string $name): string
    {
        $name = trim($name);

        if (User::where('username', 'like', '%' . $name . '%')->count() !== 0) {
            return Str::studly("{$name}-" . Str::random('5'));
        }

        return Str::studly($name);
    }

    /**
     * Register a callback that will be executed after a user has been created.
     *
     * @param \Closure|null $callback
     * @return void
     */
    public static function afterCreatingUser(?Closure $callback = null): void
    {
        static::$afterCreatingUser = $callback;
    }
}
