<?php

namespace App\Services\Image;

use App\Services\Image\Factories\ImageAbstract;
use App\Services\Interfaces\Image\ImageFactoryInterface;
use Intervention\Image\Image;

abstract class WatermarkAdder extends ImageAbstract
{
    protected $imageFactory;

    public function __construct(ImageFactoryInterface $imageFactory)
    {
        $this->imageFactory = $imageFactory;
    }

    public function generate(Image $file): Image
    {
        return $this->imageFactory->generate($file);
    }
}
