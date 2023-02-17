<?php

namespace App\Services\File\Factories;

use App\Services\Interfaces\File\FileSaver;

abstract class ImageSaver implements FileSaver
{
    public function save(mixed $file, string $path) : bool
    {
        $file->save(
            $path . '.' . $this->extension,
            100
        );

        return true;
    }
}
