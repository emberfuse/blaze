<?php

namespace App\Codes;

use Illuminate\Support\Str;
use App\Contracts\Support\Generator;

class RecoveryCode implements Generator
{
    /**
     * Generate new code.
     *
     * @return mixed
     */
    public static function generate()
    {
        return Str::random(10) . '-' . Str::random(10);
    }
}
