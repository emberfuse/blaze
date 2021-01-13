<?php

namespace App\Contracts\Support;

interface CodeGenerator
{
    /**
     * Generate new code.
     *
     * @return mixed
     */
    public static function generate();
}
