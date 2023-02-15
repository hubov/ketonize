<?php

namespace App\Services\Interfaces\Image;

use App\Services\File\SaverFactory;
use App\Services\Image\ImageFactory;
use App\Services\Image\WatermarkRecipe;
use Illuminate\Http\UploadedFile;
use Intervention\Image\ImageManager;

interface ImageParserInterface
{
    public function __construct(
        ImageManager $imageManager,
        ImageFactory $imageFactory,
        SaverFactory $saverFactory
    );
    public function getName(?string $name) : string;
    public function generate(UploadedFile $file) : bool;
}
