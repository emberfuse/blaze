<?php

namespace Cratespace\Preflight\Models;

use Throwable;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    use HasFactory;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var string[]|bool
     */
    protected $guarded = [];

    /**
     * Allow role to have given permission.
     *
     * @param \Cratespace\Preflight\Models\Permission|string $permission
     *
     * @return void
     */
    public function allowTo($permission): void
    {
        if (is_string($permission)) {
            $permission = Permission::whereLabel($permission)->first();
        }

        $this->permissions()->save($permission);
    }

    /**
     * Determine if the role has the given permission.
     *
     * @param \Cratespace\Preflight\Models\Permission|string $permission
     *
     * @return bool
     */
    public function can($permission): bool
    {
        if (is_string($permission)) {
            $permission = $this->permissions()->whereLabel($permission)->first();
        }

        try {
            return $this->permissions->contains($permission);
        } catch (Throwable $th) {
            return false;
        }
    }

    /**
     * Get all permissions the role has.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class);
    }

    /**
     * Get all users with this role.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
}
