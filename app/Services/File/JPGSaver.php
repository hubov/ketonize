<?php

namespace App\Services\File;

class JPGSaver extends ImageSaver
{
    protected $extension = 'jpg';

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
