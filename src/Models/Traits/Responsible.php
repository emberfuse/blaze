<?php

namespace Cratespace\Preflight\Models\Traits;

use Illuminate\Database\Eloquent\Model;
use Cratespace\Preflight\Support\HashId;

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
        $hashId = HashId::generate($resource->id);

        cache()->set("responsibility.{$hashId}", $resource->toJson());
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
        $isResponsible = false;

        $hashId = HashId::generate($resource->id);

        if (cache()->has($id = "responsibility.{$hashId}")) {
            $isResponsible = $resource->toArray() == json_decode(cache()->get($id), true);

            cache()->forget($id);
        }

        return $isResponsible;
    }
}
