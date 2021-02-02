<?php

namespace Cratespace\Preflight\Http\Controllers;

use Cratespace\Preflight\Http\Controllers\Concerns\ReturnsResponse;
use Cratespace\Citadel\Http\Controllers\Concerns\InteractsWithContainer;
use Cratespace\Citadel\Http\Controllers\Controller as CitadelController;

class Controller extends CitadelController
{
    use ReturnsResponse;
    use InteractsWithContainer;
}
