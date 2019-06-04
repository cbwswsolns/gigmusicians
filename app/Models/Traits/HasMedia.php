<?php

namespace App\Models\Traits;

trait HasMedia
{
    /**
     * Register a deleting model event with the dispatcher with the defined callback for deleting related media
     *
     * @return void
     */
    public static function bootHasMedia()
    {
        // Delete associated media (if existing)
        static::deleting(
            function ($model) {
                (resolve('App\Services\Media\MediaInterface'))->delete($model->getFiles());
            }
        );
    }
}
