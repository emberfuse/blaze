<?php

namespace Cratespace\Preflight\Models\Traits;

use Cratespace\Preflight\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

trait Filterable
{
    /**
     * Apply all relevant space filters.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Cratespace\Preflight\Filters\Filter  $filters
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilter($query, Filter $filters): Builder
    {
        return $filters->apply($query);
    }
}
