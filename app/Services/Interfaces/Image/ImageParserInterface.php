<?php

namespace App\Services\Interfaces\Image;

use Illuminate\Http\UploadedFile;

interface ImageParserInterface
{
    public function setFile(UploadedFile $file) : self;
    public function getName(?string $name) : string;
    public function keepOriginal() : self;
    public function makeRecipeThumbnail() : bool;
    public function makeRecipeCover() : bool;
}
