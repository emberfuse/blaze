<?php

namespace App\Http\Requests\Concerns;

use Illuminate\Support\Facades\Config;

trait ValidatesInput
{
    /**
     * Get validation rules for specified validation category.
     *
     * @param string $validationCategory
     *
     * @return array
     */
    protected function getRulesFor(string $validationCategory): array
    {
        return Config::get("rules.{$validationCategory}", []);
    }
}
