<?php

namespace App\Services\Image;

use App\Services\File\SaverFactory;
use App\Services\Interfaces\Image\ImageParserInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Image;
use Intervention\Image\ImageManager;
use Throwable;

class RecipeImageParser implements ImageParserInterface
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

    public function getName(?string $name): string
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
            $this->image = $this->imageManager
                ->make($file);

            $this->watermark = $this->imageManager
                ->make(Storage::disk(self::WATERMARK_DISK)->path('') . self::WATERMARK_FILENAME);

            $this->generateAndSaveImages();

            $this->saveOriginalImage();
        } catch (Throwable $e) {
            return false;
        }

        return true;
    }

    protected function generateAndSaveImages()
    {
        foreach ($this->fileTypes as $fileType => $imageClass) {
            foreach ($this->fileFormats as $fileFormat) {
                $this->saveImage($this->image, $fileType, $fileFormat);
            }
        }
    }

    protected function saveOriginalImage()
    {
        $this->saverFactory
            ->get('jpg')
            ->save(
                $this->image,
                $this->getLocalPath() . $this->name
            );
    }

    protected function saveImage(Image $image, string $fileType, string $fileFormat)
    {
        $watermarkDecorator = new WatermarkRecipe(
            $this->imageFactory
                ->get($this->fileTypes[$fileType]),
            $this->watermark
        );

        $this->saverFactory
            ->get($fileFormat)
            ->save(
                $watermarkDecorator->generate($image),
                $this->getPublicPath() . $fileType . 's/' . $this->name
            );
    }

    protected function getLocalPath(): string
    {
        return Storage::disk(self::STORAGE_DISK_LOCAL)->path('');
    }

    protected function getPublicPath(): string
    {
        return Storage::disk(self::STORAGE_DISK_PUBLIC)->path('');
    }
}
