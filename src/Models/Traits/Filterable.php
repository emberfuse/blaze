<?php

namespace Emberfuse\Blaze\Models\Traits;

use Emberfuse\Blaze\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

trait Filterable
{
    /**
     * Apply all relevant space filters.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Emberfuse\Blaze\Filters\Filter  $filters
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilter($query, Filter $filters): Builder
    {
        return $filters->apply($query);
    }
}
