<?php

namespace App\Models\Traits;

use Illuminate\Support\Str;

trait Indexable
{
    /**
     * Get singular name of model class in lowercase.
     *
     * @return string
     */
    public function getResource(): string
    {
        return Str::singular($this->getTable());
    }
}
