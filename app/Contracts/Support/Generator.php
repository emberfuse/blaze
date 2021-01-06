<?php

namespace App\Contracts\Support;

interface Generator
{
    /**
     * Generate new code.
     *
     * @return mixed
     */
    public static function generate();
}
