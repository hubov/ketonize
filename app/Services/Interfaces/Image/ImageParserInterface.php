<?php

namespace App\Services\Interfaces\Image;

use Illuminate\Http\UploadedFile;

interface ImageParserInterface
{
    public function getName(?string $name) : string;
    public function generate(UploadedFile $file) : bool;
}
