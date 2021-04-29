<?php

namespace Cratespace\Preflight\Support\Traits;

trait Fillable
{
    /**
     * Filter and etract only data allowable to be changed.
     *
     * @param \Illuminate\Database\Eloquent\Model|string $resource
     * @param array                                      $data
     *
     * @return array
     */
    public function filterFillable($resource, array $data): array
    {
        if (is_string($resource)) {
            $resource = new $resource();
        }

        return array_filter(
            $data,
            fn (string $key): bool => in_array($key, $resource->getFillable()),
            \ARRAY_FILTER_USE_KEY
        );
    }
}
