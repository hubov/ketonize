<?php

namespace App\Services\File\Factories;

class WebpSaver extends ImageSaver
{
    protected $extension = 'webp';

    public function save(mixed $file, string $path) : bool
    {
        parent::save(
            $file,
            $path
        );

        return true;
    }
}
