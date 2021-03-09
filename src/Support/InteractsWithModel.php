<?php

namespace Cratespace\Preflight\Support;

use Illuminate\Database\Eloquent\Model;

trait InteractsWithModel
{
    /**
     * Instance of model being queried.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;

    /**
     * Instantiate the model to be queried.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    protected function model(): Model
    {
        return app()->make($this->model);
    }
}
