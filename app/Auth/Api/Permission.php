<?php

namespace App\Auth\Api;

class Permission
{
    /**
     * The permissions that exist within the application.
     *
     * @var array
     */
    public static array $permissions = [];

    /**
     * The default permissions that should be available to new entities.
     *
     * @var array
     */
    public static array $defaultPermissions = [];

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
     * @param array $permissions
     *
     * @return \App\Auth\Api\Permission
     */
    public static function permissions(array $permissions): Permission
    {
        static::$permissions = $permissions;

        return new static();
    }

    /**
     * Define the default permissions that should be available to new API tokens.
     *
     * @param array $permissions
     *
     * @return \App\Auth\Api\Permission
     */
    public static function defaultApiTokenPermissions(array $permissions): Permission
    {
        static::$defaultPermissions = $permissions;

        return new static();
    }

    /**
     * Return the permissions in the given list that are actually defined permissions for the application.
     *
     * @param array $permissions
     *
     * @return array
     */
    public static function validPermissions(array $permissions): array
    {
        return array_values(array_intersect($permissions, static::$permissions));
    }
}
