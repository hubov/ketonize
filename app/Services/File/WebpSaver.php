<?php

namespace App\Services\File;

class WebpSaver extends ImageSaver
{
    protected $extension = 'webp';

    public function save(mixed $file, string $path, $extension = null) : bool
    {
        parent::save(
            $file,
            $path,
            $this->extension
        );

        return true;
    }
}
