<?php

namespace Cratespace\Preflight\Tests\Fixtures;

use Laravel\Sanctum\HasApiTokens;
use Cratespace\Sentinel\Models\Traits\HasProfilePhoto;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasProfilePhoto;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
}
