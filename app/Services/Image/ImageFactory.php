<?php

namespace App\Services\Image;

use App\Services\Interfaces\Image\ImageFactoryInterface;
use Closure;
use Intervention\Image\Image;

abstract class ImageFactory implements ImageFactoryInterface
{
    public function generate(Image $file) : Image
    {
        return clone $file;
    }

    protected function keepRatio() : Closure
    {
        return function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        };
    }
}
