<?php

namespace App\Services\Image;

use App\Services\File\SaverFactory;
use App\Services\Image\Factories\ImageFactory;
use App\Services\Interfaces\Image\ImageProcessorInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Image;
use Intervention\Image\ImageManager;
use Throwable;

class RecipeImageProcessor implements ImageProcessorInterface
{
    public const STORAGE_DISK_LOCAL = 'local_recipe_images';
    public const STORAGE_DISK_PUBLIC = 'public_recipe_images';
    public const WATERMARK_DISK = 'logo';
    public const WATERMARK_FILENAME = 'black-logo-no-background.png';
    protected $imageManager;
    protected $watermark;
    protected $imageFactory;
    protected $saverFactory;
    protected $name;
    protected $image;
    protected $fileTypes = [
        'cover' => 'RecipeCover',
        'thumbnail' => 'RecipeThumbnail'
    ];
    protected $fileFormats = ['jpg', 'webp'];

    public function __construct(
        ImageManager $imageManager,
        ImageFactory $imageFactory,
        SaverFactory $saverFactory
    ) {
        $this->imageManager = $imageManager;
        $this->imageFactory = $imageFactory;
        $this->saverFactory = $saverFactory;

        return $this;
    }

    public function getName(?string $name = NULL): string
    {
        if (isset($name)) {
            $this->name = Str::slug($name);
        } else {
            $this->name = md5(uniqid(rand(),true));
        }

        $this->name .=  '-' . $this->getHash(6);

        return $this->name;
    }

    protected function getHash(int $length) : string
    {
        $characterList = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z'];

        shuffle($characterList);

        return implode('', array_slice($characterList, 0, $length));
    }

    public function generate(UploadedFile $file) : bool
    {
        try {
            $this->createNameIfNotSet();

            $this->image = $this->imageManager
                ->make($file);
            $this->saveOriginalImage();

            $this->addWatermark();

            $this->generateAndSaveImages();
        } catch (Throwable $e) {
            dump($e->getMessage());
            echo'<pre>';
            print_r($e->getTrace());
            echo'</pre>';
            dd('');
            return false;
        }

        return true;
    }

    protected function createNameIfNotSet() : void
    {
        if (!isset($this->name)) {
            $this->getName();
        }
    }

    protected function saveOriginalImage() : void
    {
        $this->saverFactory
            ->get('jpg')
            ->save(
                $this->image,
                $this->getLocalImageFilePath()
            );
    }

    public function getLocalImageFilePath() : string
    {
        return $this->getLocalPath() . $this->name;
    }

    public function getLocalPath(): string
    {
        return Storage::disk(self::STORAGE_DISK_LOCAL)->path('');
    }

    protected function addWatermark() : void
    {
        $this->watermark = $this->imageManager
            ->make($this->getWatermarkFilePath());

        $watermarkDecorator = new WatermarkRecipe(
            $this->imageFactory
                ->get('RecipeCover'),
            $this->watermark
        );

        $this->image = $watermarkDecorator->generate($this->image);
    }

    protected function generateAndSaveImages() : void
    {
        foreach ($this->fileTypes as $fileType => $imageClass) {
            $image = $this->imageFactory
                ->get($imageClass)
                ->generate($this->image);
            foreach ($this->fileFormats as $fileFormat) {
                $this->saveImage($image, $fileType, $fileFormat);
            }
        }
    }

    protected function saveImage(Image $image, string $fileType, string $fileFormat) : void
    {
        $this->saverFactory
            ->get($fileFormat)
            ->save(
                $image,
                $this->getPublicImageFilePath($fileType)
            );
    }

    public function getPublicImageFilePath(string $fileType) : string
    {
        return $this->getPublicPath() . $fileType . 's/' . $this->name;
    }

    public function getPublicPath(): string
    {
        return Storage::disk(self::STORAGE_DISK_PUBLIC)->path('');
    }

    public function getWatermarkFilePath() : string
    {
        return Storage::disk(self::WATERMARK_DISK)->path('') . self::WATERMARK_FILENAME;
    }
}
