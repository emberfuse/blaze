<?php

namespace Cratespace\Preflight\Models\Traits;

use Illuminate\Database\Eloquent\Model;

trait Responsible
{
    /**
     * The responsibility of this resource item.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $responsibility;

    /**
     * Set the responsibility of this resource.
     *
     * @param \Illuminate\Database\Eloquent\Model $resource
     *
     * @return void
     */
    public function setResponsibility(Model $resource): void
    {
        $this->responsibility = $resource;
    }

    /**
     * Determine if this resource is responsible for the given resource.
     *
     * @param \Illuminate\Database\Eloquent\Model $resource
     *
     * @return bool
     */
    public function isResponsibleFor(Model $resource): bool
    {
        return (bool) optional($this->responsibility)->is($resource);
    }
}
