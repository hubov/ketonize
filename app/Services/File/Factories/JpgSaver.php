<?php

namespace App\Services\File\Factories;

class JpgSaver extends ImageSaver
{
    protected $extension = 'jpg';

    public function save(mixed $file, string $path) : bool
    {
        parent::save(
            $file,
            $path
        );

        return true;
    }
}
