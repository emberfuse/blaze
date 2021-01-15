<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Permission extends Model
{
    use HasFactory;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var string[]|bool
     */
    protected $guarded = [];

    /**
     * The permissions that exist within the application.
     *
     * @var array
     */
    public static $permissions = [];

    /**
     * The default permissions that should be available to new entities.
     *
     * @var array
     */
    public static $defaultPermissions = [];

    /**
     * Determine if any permissions have been registered with application.
     *
     * @return bool
     */
    public static function hasPermissions(): bool
    {
        return count(static::$permissions) > 0;
    }

    /**
     * Define the available API token permissions.
     *
     * @param  array  $permissions
     * @return \App\Models\Permission
     */
    public static function permissions(array $permissions): Permission
    {
        static::$permissions = (array) static::setPermissions($permissions);

        return new static();
    }

    /**
     * Define the default permissions that should be available to new API tokens.
     *
     * @param  array  $permissions
     * @return \App\Models\Permission
     */
    public static function defaultApiTokenPermissions(array $permissions): Permission
    {
        static::$defaultPermissions = (array) static::setPermissions($permissions);

        return new static();
    }

    /**
     * Creates database entry for each permission in the given array.
     *
     * @param array $permissions
     * @return array
     */
    protected static function setPermissions(array $permissions): array
    {
        return collect($permissions)->map(function ($permission) {
            if (! static::whereTitle($permission)->exists()) {
                static::create(['title' => $permission]);

                return $permission;
            }
        })->toArray();
    }

    /**
     * Return the permissions in the given list that are actually defined permissions for the application.
     *
     * @param  array  $permissions
     * @return array
     */
    public static function validPermissions(array $permissions): array
    {
        return collect($permissions)->filter(function ($permission) {
            if (static::whereTitle($permission)->exists()) {
                return $permission;
            }
        })->values()->toArray();
    }
}
