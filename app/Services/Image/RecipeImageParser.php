<?php

namespace App\Services\Image;

use App\Services\File\JPGSaver;
use App\Services\File\WebpSaver;
use App\Services\Interfaces\Image\ImageParserInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
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
    protected $cover;
    protected $thumbnail;
    protected $jpgSaver;
    protected $webpSaver;
    protected $watermark;
    protected $name;

    public function __construct(ImageManager $imageManager, RecipeCover $cover, RecipeThumbnail $thumbnail, JPGSaver $jpgSaver, WebpSaver $webpSaver, Watermark $watermark)
    {
        $this->imageManager = $imageManager;
        $this->cover = $cover;
        $this->thumbnail = $thumbnail;
        $this->jpgSaver = $jpgSaver;
        $this->webpSaver = $webpSaver;
        $this->watermark = $watermark;

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
            $image = $this->imageManager
                ->make($file);

            $this->watermark->create(
                $this->imageManager
                    ->make(Storage::disk(self::WATERMARK_DISK)->path('') . self::WATERMARK_FILENAME)
            );

            $fileTypes = ['cover', 'thumbnail'];
            $fileFormats = ['jpg', 'webp'];

            foreach ($fileTypes as $fileType) {
                foreach ($fileFormats as $fileFormat) {
                    $this->saveImage($image, $fileType, $fileFormat);
                }
            }

            // keep original
            $this->jpgSaver->save(
                $image,
                $this->getLocalPath() . $this->name
            );
        } catch (Throwable $e) {
            Log::notice($e->getMessage());
            print $e->getMessage();

            return false;
        }

        return true;
    }

    protected function saveImage(Image $image, string $fileType, string $fileFormat)
    {
        $saverName = $fileFormat . 'Saver';

        $this->$saverName->save(
            $this->watermark->generate(
                $this->$fileType->generate($image)
            ),
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
