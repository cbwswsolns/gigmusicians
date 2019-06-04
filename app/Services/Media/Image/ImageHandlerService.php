<?php

namespace App\Services\Media\Image;

use ImageIntervention;

class ImageHandlerService
{
    /**
     * Make resized image for media image objects
     *
     * @param string $originalPath [original file path]
     * @param string $targetPath   [target file path of resized image]
     * @param int    $width        [target image width]
     * @param int    $height       [target image height]
     *
     * @return void
     */
    public function makeResizedImage(string $originalPath, string $targetPath, int $width, int $height)
    {
            // Crop the original image and save the cropped image file in storage
            ImageIntervention::make($originalPath)->resize($width, $height)->save($targetPath);
    }
}
