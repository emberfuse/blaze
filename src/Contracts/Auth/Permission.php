<?php

namespace Emberfuse\Blaze\Contracts\Auth;

interface Permission
{
    /**
     * Determine if any permissions have been registered with Preflight.
     *
     * @return bool
     */
    public static function hasPermissions(): bool;

    /**
     * Return the permissions in the given list that are actually defined permissions for the application.
     *
     * @param array $permissions
     *
     * @return array
     */
    public static function validPermissions(array $permissions): array;
}
