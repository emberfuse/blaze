<?php

namespace Cratespace\Preflight\Tests\Fixtures;

use Illuminate\Database\Eloquent\Model;
use Cratespace\Preflight\Models\Traits\Directable;
use Cratespace\Preflight\Models\Traits\Presentable;

class MockModel extends Model
{
    use Presentable;
    use Directable;
}
