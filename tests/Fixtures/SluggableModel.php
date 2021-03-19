<?php

namespace Cratespace\Preflight\Tests\Fixtures;

use Illuminate\Database\Eloquent\Model;
use Cratespace\Preflight\Models\Traits\Sluggable;

class SluggableModel extends Model
{
    use Sluggable;

    protected $table = 'sluggable_models';

    protected $guarded = [];
}
