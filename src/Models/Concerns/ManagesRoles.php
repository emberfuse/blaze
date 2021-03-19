<?php

namespace Cratespace\Preflight\Models\Concerns;

use Cratespace\Preflight\Models\Role;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait ManagesRoles
{
    /**
     * Determine if user is assigned the given role.
     *
     * @return bool
     *
     * @param mixed $role
     */
    public function hasRole($role): bool
    {
        if (is_string($role)) {
            $role = $this->findRole($role);
        }

        if (is_null($role)) {
            return false;
        }

        return $this->role->is($role);
    }

    /**
     * Assign role to model.
     *
     * @param \App\Models\Role|string $role
     *
     * @return void
     */
    public function assignRole($role): void
    {
        if (is_string($role)) {
            $role = $this->findRole($role);
        }

        $this->update(['role_id' => $role->id]);
    }

    /**
     * Find given role in database.
     *
     * @param string $role
     *
     * @return \App\Models\Role|null $role
     */
    public function findRole(string $role): ?Role
    {
        return Role::whereName($role)->first();
    }

    /**
     * Get the role of the model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function role(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }
}
