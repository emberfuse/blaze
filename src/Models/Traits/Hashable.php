<?php

namespace Emberfuse\Blaze\Models\Traits;

use Illuminate\Database\Eloquent\Model;
use Emberfuse\Blaze\Support\HashId;

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
            $model->forceFill([
                'code' => HashId::generate($model->id),
            ])->saveQuietly();
        });
    }
}
