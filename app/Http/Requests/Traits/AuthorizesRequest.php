<?php

namespace App\Http\Requests\Traits;

use Illuminate\Database\Eloquent\Model;

trait AuthorizesRequest
{
    /**
     * Determine if user making request is authenticated.
     *
     * @return bool
     */
    protected function authenticated(): bool
    {
        return auth()->check() && $this->user()->is(auth()->user());
    }

    /**
     * Determine if requested resource belongs to the user making the request.
     *
     * @param \Illuminate\Database\Eloquent\Model $resource
     *
     * @return bool
     */
    protected function resourceBelongsToUser(Model $resource): bool
    {
        return $this->authenticated() && $this->user()->is($resource->user);
    }

    /**
     * Determine if the resource is available to perform an action on.
     *
     * @param \Illuminate\Database\Eloquent\Model $resource
     *
     * @return bool
     */
    protected function resourceIsAvailable(Model $resource): bool
    {
        return $resource->isAvailable();
    }
}
