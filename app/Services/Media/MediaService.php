<?php

namespace App\Services\Media;

use App\Services\Media\MediaInterface;

use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;

use Illuminate\Support\Facades\Storage;

class MediaService implements MediaInterface
{
    /**
     * Store file method
     *
     * @param \Illuminate\Http\UploadedFile $file [the file to store]
     * @param string                        $path [the path to the storage location]
     *
     * @return string|false
     */
    public function store(UploadedFile $file, $path)
    {
        return Storage::putFile($path, new File($file->getRealPath()));
    }


    /**
     * Delete method for media objects
     *
     * @param string|array $paths [path(s) to file(s) to be deleted]
     *
     * @return bool
     */
    public function delete($paths)
    {
        return Storage::delete($paths);
    }
}
