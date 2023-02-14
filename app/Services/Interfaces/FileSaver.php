<?php

namespace App\Services\Interfaces;

interface FileSaver
{
    public function save(mixed $file, string $path, ?string $extension) : bool;
}
