<?php

namespace App\Services\File;

use App\Services\Interfaces\File\FileSaver;

class Saver
{
    protected $saver;

    public function setSaver(FileSaver $saver) : self
    {
        $this->saver = $saver;

        return $this;
    }

    public function save(mixed $file, string $path) : bool
    {
        return $this->saver->save($file, $path);
    }
}
