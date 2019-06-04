<?php

namespace App\Services\Media;

use Illuminate\Http\UploadedFile;

interface MediaInterface
{

    /**
     * Store method for media objects
     *
     * @param \Illuminate\Http\UploadedFile $file [the file to store]
     * @param string                        $path [the path to the storage location]
     *
     * @return mixed
     */
    public function store(UploadedFile $file, $path);


    /**
     * Delete method for media objects
     *
     * @param string|array $paths [path(s) to file(s) to be deleted]
     *
     * @return mixed
     */
    public function delete($paths);
}
