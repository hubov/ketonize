<?php

namespace App\Services\Interfaces\Image;

use Intervention\Image\Image;

interface ImageFactoryInterface
{
    public function generate(Image $file) : Image;
}
