<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use App\Models\Traits\HasProfilePhoto;
use Illuminate\Notifications\Notifiable;
use App\Models\Concerns\InteractsWithSession;
use App\Models\Traits\TwoFactorAuthenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;
    use HasApiTokens;
    use HasProfilePhoto;
    use InteractsWithSession;
    use TwoFactorAuthenticatable;

    /**
     * Preferred route key name.
     *
     * @var string
     */
    protected static $routeKey = 'username';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'locked',
        'password',
        'username',
        'settings',
        'profile_photo_path',
        'two_factor_enabled',
        'two_factor_secret',
        'two_factor_recovery_codes',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'two_factor_enabled' => 'boolean',
        'settings' => 'array',
        'locked' => 'boolean',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
        'sessions',
    ];

    /**
     * Determine if the user's account is locked.
     *
     * @return bool
     */
    public function isLocked(): bool
    {
        return (bool) $this->locked;
    }

    /**
     * Get user sessions data.
     *
     * @return array
     */
    public function getSessionsAttribute()
    {
        return $this->sessions(request())->all();
    }
}
