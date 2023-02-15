<?php

namespace App\Services\Image;

use App\Services\Interfaces\Image\ImageFactoryInterface;
use Intervention\Image\Image;

class WatermarkRecipe extends WatermarkAdder
{
    protected $watermark;

    public function __construct(ImageFactoryInterface $imageFactory, Image $watermark)
    {
        parent::__construct($imageFactory);

        $this->watermark = $watermark;
    }

    public function generate(Image $file): Image
    {
        $file = parent::generate($file);

        $watermark = $this->watermark
            ->resize(
                $file->width() * 0.25,
                null,
                $this->keepRatio()
            );

        return $file->insert($watermark, 'bottom-right', 10, 10);
    }
}
