<?php

namespace App\Codes;

use Illuminate\Support\Str;
use App\Contracts\Support\CodeGenerator;

class RecoveryCode implements CodeGenerator
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
