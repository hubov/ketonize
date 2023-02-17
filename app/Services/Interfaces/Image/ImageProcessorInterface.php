<?php

namespace App\Services\Interfaces\Image;

use App\Services\File\Factories\SaverFactory;
use App\Services\Image\Factories\ImageFactory;
use Illuminate\Http\UploadedFile;
use Intervention\Image\ImageManager;

interface ImageProcessorInterface
{
    public function __construct(
        ImageManager $imageManager,
        ImageFactory $imageFactory,
        SaverFactory $saverFactory
    );
    public function getName(?string $name) : string;
    public function generate(UploadedFile $file) : bool;
}
