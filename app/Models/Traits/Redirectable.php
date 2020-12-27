<?php

namespace App\Models\Traits;

trait Redirectable
{
    /**
     * Get full path to single resource page.
     *
     * @return string
     */
    public function getPathAttribute()
    {
        return route($this->getTable() . '.' . $this->getResourceView(), $this);
    }

    /**
     * Get view method of single resource.
     *
     * @return string
     */
    protected function getResourceView(): string
    {
        if (isset($this->resourceView)) {
            return $this->resourceView;
        }

        return 'show';
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        if (isset(static::$routeKey)) {
            return static::$routeKey;
        }

        return 'id';
    }
}
