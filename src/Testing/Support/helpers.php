<?php

if (! function_exists('create')) {
    /**
     * Create a model factory builder for a given class, name, and amount.
     *
     * @param string      $class
     * @param array       $attributes
     * @param string|null $condition
     * @param int         $times
     *
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection
     */
    function create(
        string $class,
        array $attributes = [],
        ?string $condition = null,
        ?int $times = null
    ) {
        $factory = $class::factory();

        if (! is_null($condition)) {
            $factory = $factory->{$condition}();
        }

        return $factory->count($times)->create($attributes);
    }
}

if (! function_exists('make')) {
    /**
     * Create a model factory builder for a given class, name, and amount.
     *
     * @param string      $class
     * @param array       $attributes
     * @param string|null $condition
     * @param int         $times
     *
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection
     */
    function make(
        string $class,
        array $attributes = [],
        ?string $condition = null,
        ?int $times = null
    ) {
        $factory = $class::factory();

        if (! is_null($condition)) {
            $factory = $factory->{$condition}();
        }

        return $factory->count($times)->make($attributes);
    }
}
