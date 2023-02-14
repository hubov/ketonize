<?php

namespace App\Services\Image;

use Intervention\Image\Image;

class RecipeCover extends ImageFactory
{
    public const IMAGE_WIDTH = 860;
    public const IMAGE_HEIGHT = null;

    public function generate(Image $file) : Image
    {
        return parent::generate($file)
            ->resize(
                self::IMAGE_WIDTH,
                self::IMAGE_HEIGHT,
                $this->keepRatio());
    }
}
