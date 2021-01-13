<?php

namespace App\Auth\Actions;

use Closure;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Contracts\Auth\DeletesUsers;
use Illuminate\Contracts\Auth\Authenticatable;

class DeleteUser implements DeletesUsers
{
    /**
     * Callback to run to delete all resources associated with the user being deleted.
     *
     * @var \Closure
     */
    protected static $deleteUserResources;

    /**
     * Delete the given user.
     *
     * @param \Illuminate\Contracts\Auth\Authenticatable $user
     * @return void
     */
    public function delete(Authenticatable $user): void
    {
        DB::transaction(function () use ($user) {
            tap($user, function (User $user) {
                $this->deleteUserResources($user);

                $this->deleteUserProfiles($user);
            })->delete();
        }, 2);
    }

    /**
     * Delete all resources that belong to the user.
     *
     * @param \App\Models\User $user
     * @return void
     */
    protected function deleteUserResources(User $user): void
    {
        if (is_null(static::$deleteUserResources)) {
            return;
        }

        call_user_func(static::$deleteUserResources, $user);
    }

    /**
     * Undocumented function
     *
     * @param \Closure $callback
     * @return void
     */
    public static function duringUserResourcesConcern(Closure $callback): void
    {
        static::$deleteUserResources = $callback;
    }

    /**
     * Delete user profile details.
     *
     * @param \App\Models\User $user
     * @return void
     */
    protected function deleteUserProfiles(User $user): void
    {
        $user->deleteProfilePhoto();

        $user->tokens->each->delete();
    }
}
