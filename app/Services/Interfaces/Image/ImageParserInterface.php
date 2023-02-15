<?php

namespace App\Services\Interfaces\Image;

use App\Services\File\SaverFactory;
use App\Services\Image\ImageFactory;
use App\Services\Image\Watermark;
use Illuminate\Http\UploadedFile;
use Intervention\Image\ImageManager;

interface ImageParserInterface
{
    public function __construct(
        ImageManager $imageManager,
        ImageFactory $imageFactory,
        SaverFactory $saverFactory,
        Watermark $watermark
    );
    public function getName(?string $name) : string;
    public function generate(UploadedFile $file) : bool;
}
