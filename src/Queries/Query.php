<?php

namespace Emberfuse\Blaze\Queries;

use RuntimeException;
use Illuminate\Database\Eloquent\Builder;
use Emberfuse\Scorch\Support\Concerns\InteractsWithContainer;

abstract class Query
{
    use InteractsWithContainer;

    /**
     * Instance of model being queried.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;

    /**
     * Begin querying the model.
     *
     * @return Builder
     */
    public function query(): Builder
    {
        if (is_null($this->model)) {
            throw new RuntimeException('Model not set');
        }

        if (is_string($this->model)) {
            $this->model = $this->resolve($this->model);
        }

        return $this->model->query();
    }
}
