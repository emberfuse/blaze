<?php

namespace Emberfuse\Blaze\Http\Controllers;

use Emberfuse\Blaze\Http\Controllers\Concerns\ReturnsResponse;
use Emberfuse\Scorch\Http\Controllers\Controller as ScorchController;

class Controller extends ScorchController
{
    use ReturnsResponse;
}
