<?php

namespace Cratespace\Preflight\Models\Traits;

use Illuminate\Database\Eloquent\Model;
use Cratespace\Preflight\Support\HashId;

trait Hashable
{
    /**
     * Boot all of the bootable traits on the model.
     *
     * @return void
     */
    protected static function bootHashable(): void
    {
        static::created(function (Model $model): void {
            $model->update(['code' => HashId::generate($model->id)]);
        });
    }
}
