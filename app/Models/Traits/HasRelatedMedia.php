<?php

namespace App\Models\Traits;

trait HasRelatedMedia
{
    /**
     * Register a deleting model event with the dispatcher with the defined callback for deleting related media
     *
     * @return void
     */
    public static function bootHasRelatedMedia()
    {
        // Delete associated images if they exist.
        static::deleting(
            function ($model) {
                $images = $model->images()->get();
                $musicFiles = $model->musicfiles()->get();

                // Fetch all media path/filenames to an array
                $mediaToDelete = array_merge(
                    $images->pluck('filename')->toArray(),
                    $images->pluck('crop_filename')->toArray(),
                    $musicFiles->pluck('filename')->toArray()
                );

                (resolve('App\Services\Media\MediaInterface'))->delete($mediaToDelete);
            }
        );
    }
}
