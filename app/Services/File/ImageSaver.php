<?php

namespace App\Services\File;

use App\Services\Interfaces\FileSaver;

abstract class ImageSaver implements FileSaver
{
    public function save(mixed $file, string $path, ?string $extension) : bool
    {
        $file->save(
            $path . '.' . $extension,
            100
        );

        return true;
    }
}
