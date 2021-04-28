<?php

namespace Cratespace\Preflight\Support;

use Illuminate\Database\Eloquent\Model;

class AbstractEloquent
{
    /**
     * Instance of model being queried.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;

    /**
     * Create new AbstractEloquent instance.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @return void
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }
}
