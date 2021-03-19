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

        return $this->roles->contains($role);
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

        $this->roles()->save($role);
    }

    /**
     * Find given role in database.
     *
     * @param string|null $role
     *
     * @return \App\Models\Role|\Illuminate\Database\Eloquent\Collection|null $role
     */
    public function findRole(?string $role = null)
    {
        if (is_null($role)) {
            return $this->roles;
        }

        return Role::whereName($role)->first();
    }

    /**
     * Get the role of the model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }
}
