<?php

namespace Cratespace\Preflight\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Cratespace\Contracts\Auth\Permission as PermissionContract;

class Permission extends Model implements PermissionContract
{
    use HasFactory;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var string[]|bool
     */
    protected $guarded = [];

    /**
     * Determine if any permissions have been registered with Cratespace.
     *
     * @return bool
     */
    public static function hasPermissions(): bool
    {
        return static::all()->count() > 0;
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
        return collect($permissions)
            ->filter(function ($permission) {
                return static::whereLabel($permission)->exists();
            })->toArray();
    }

    /**
     * Get all roles the permission is assigned to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }
}
