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
    protected static $model;

    /**
     * Instantiate the model to be queried.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    protected static function model(): Model
    {
        return app()->make(static::$model);
    }
}
