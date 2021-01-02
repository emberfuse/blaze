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
    protected static $afterCreatingUserCallback;

    /**
     * Create a newly registered user.
     *
     * @param array $data
     *
     * @return mixed
     */
    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            return tap($this->createUser($data), function (User $user) use ($data) {
                if (static::$afterCreatingUserCallback) {
                    return call_user_func_array(
                        static::$afterCreatingUserCallback,
                        [$user, $data]
                    );
                }

                return $user;
            });
        });
    }

    /**
     * Create new user profile.
     *
     * @param array $data
     *
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
     *
     * @return string
     */
    protected function makeUsername(string $name): string
    {
        if (Str::contains($name, '.')) {
            [$title, $name] = explode('.', $name);
        }

        [$firstName, $lastName] = explode(' ', $name);

        $count = User::where('username', 'like', '%' . $firstName . '%')->count();

        if ($count !== 0) {
            return Str::studly($firstName . $lastName);
        }

        return $firstName;
    }

    /**
     * Register a callback that will be executed after a user has been created.
     *
     * @param \Closure $callback
     *
     * @return void
     */
    public static function afterCreatingUser(Closure $callback): void
    {
        static::$afterCreatingUserCallback = $callback;
    }
}
