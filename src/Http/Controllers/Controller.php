<?php

namespace Cratespace\Preflight\Http\Controllers;

use Cratespace\Preflight\Http\Controllers\Concerns\ReturnsResponse;
use Cratespace\Sentinel\Http\Controllers\Controller as SentinelController;

class Controller extends SentinelController
{
    use ReturnsResponse;
}
