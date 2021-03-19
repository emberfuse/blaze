<?php

namespace Cratespace\Preflight\Tests\Fixtures;

use Cratespace\Preflight\Models\Concerns\ManagesRoles;
use Cratespace\Sentinel\Models\Traits\HasApiTokens;
use Cratespace\Sentinel\Models\Traits\HasProfilePhoto;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasProfilePhoto;
    use ManagesRoles;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
}
