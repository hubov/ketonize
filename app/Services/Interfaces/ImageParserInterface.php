<?php

namespace App\Services\Interfaces;

use Illuminate\Http\UploadedFile;

interface ImageParserInterface
{
    public function setFile(UploadedFile $file) : self;
    public function getName(?string $name) : string;
    public function keepOriginal() : self;
}
