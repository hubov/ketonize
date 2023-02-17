<?php

namespace App\Services\Image\Factories;

use Intervention\Image\Image;

class RecipeThumbnail extends ImageAbstract
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
