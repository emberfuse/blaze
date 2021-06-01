<?php

namespace Emberfuse\Blaze\Tests\Fixtures;

use Illuminate\Database\Eloquent\Model;
use Emberfuse\Blaze\Models\Traits\Sluggable;

class SluggableModel extends Model
{
    use Sluggable;

    protected $table = 'sluggable_models';

    protected $guarded = [];
}
