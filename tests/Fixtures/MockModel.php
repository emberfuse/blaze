<?php

namespace Emberfuse\Blaze\Tests\Fixtures;

use Illuminate\Database\Eloquent\Model;
use Emberfuse\Blaze\Models\Traits\Directable;
use Emberfuse\Blaze\Models\Traits\Presentable;

class MockModel extends Model
{
    use Presentable;
    use Directable;
}
