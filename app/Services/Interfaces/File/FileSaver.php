<?php

namespace App\Services\Interfaces\File;

interface FileSaver
{
    public function save(mixed $file, string $path) : bool;
}
