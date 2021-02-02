<?php

namespace Cratespace\Preflight\API;

class Permission
{
    /**
     * The roles that are available to assign to users.
     *
     * @var array
     */
    public static $roles = [];

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
     * Determine if Preflight has registered roles.
     *
     * @return bool
     */
    public static function hasRoles(): bool
    {
        return count(static::$roles) > 0;
    }

    /**
     * Find the role with the given key.
     *
     * @param string $key
     *
     * @return \Cratespace\Preflight\API\Role
     */
    public static function findRole(string $key): Role
    {
        return static::$roles[$key] ?? null;
    }

    /**
     * Define a role.
     *
     * @param string $key
     * @param string $name
     * @param array  $permissions
     *
     * @return \Cratespace\Preflight\API\Role
     */
    public static function role(string $key, string $name, array $permissions): Role
    {
        static::$permissions = collect(array_merge(static::$permissions, $permissions))
            ->unique()
            ->sort()
            ->values()
            ->all();

        return tap(new Role($key, $name, $permissions), function ($role) use ($key) {
            static::$roles[$key] = $role;
        });
    }

    /**
     * Determine if any permissions have been registered with Preflight.
     *
     * @return bool
     */
    public static function hasPermissions()
    {
        return count(static::$permissions) > 0;
    }

    /**
     * Define the available API token permissions.
     *
     * @param array $permissions
     *
     * @return static
     */
    public static function permissions(array $permissions)
    {
        static::$permissions = $permissions;

        return new static();
    }

    /**
     * Define the default permissions that should be available to new API tokens.
     *
     * @param array $permissions
     *
     * @return static
     */
    public static function defaultApiTokenPermissions(array $permissions)
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
    public static function validPermissions(array $permissions)
    {
        return array_values(array_intersect($permissions, static::$permissions));
    }
}
