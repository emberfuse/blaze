<?php

namespace App\Http\Requests\Concerns;

use Illuminate\Support\Facades\Config;

trait InputValidationRules
{
    /**
     * Get validation rules for specified validation category.
     *
     * @param string $validationCategory
     * @param array  $additionalRules
     *
     * @return array
     */
    protected function getRulesFor(string $validationCategory, array $additionalRules = []): array
    {
        return array_merge(
            Config::get("rules.{$validationCategory}", []),
            $additionalRules
        );
    }

    /**
     * Rules to validate password input.
     *
     * @param array $overrides
     * @return array
     */
    protected function passwordRules(array $overrides = []): array
    {
        return array_merge(['password' => ['password', 'required', 'string']], $overrides);
    }
}
