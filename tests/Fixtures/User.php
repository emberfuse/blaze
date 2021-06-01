<?php

namespace Emberfuse\Blaze\Tests\Fixtures;

use Emberfuse\Blaze\Models\Concerns\ManagesRoles;
use Emberfuse\Scorch\Models\Traits\HasApiTokens;
use Emberfuse\Scorch\Models\Traits\HasProfilePhoto;
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
