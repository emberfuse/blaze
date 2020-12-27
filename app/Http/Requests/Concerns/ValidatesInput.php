<?php

namespace App\Http\Requests\Concerns;

use Illuminate\Support\Facades\Config;

trait ValidatesInput
{
    /**
     * Get the validation rules that apply to the resource.
     *
     * @param string $key
     * @param array  $additionalRules
     *
     * @return array
     */
    protected function getRulesFor(string $key, array $additionalRules = []): array
    {
        return array_merge(Config::get("rules.{$key}"), $additionalRules);
    }
}
