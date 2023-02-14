<?php

namespace App\Services\Image;

use Intervention\Image\Image;

class Watermark extends ImageFactory
{
    protected $watermark;

    public function create(Image $watermarkImage) : self
    {
        $this->watermark = parent::generate($watermarkImage);

        return $this;
    }

    public function generate(Image $file) : Image
    {
        $watermark = parent::generate($this->watermark)
            ->resize(
                $file->width() * 0.25,
                null,
                $this->keepRatio());

        return $file->insert($watermark, 'bottom-right', 10, 10);
    }
}
