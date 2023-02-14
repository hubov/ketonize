<?php

namespace App\Services;

use App\Services\Interfaces\ImageParserInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Image;
use Intervention\Image\ImageManager;
use Throwable;

class ImageParser implements ImageParserInterface
{
    const STORAGE_DISK_LOCAL = 'local_recipe_images';
    const STORAGE_DISK_PUBLIC = 'public_recipe_images';
    const RECIPE_COVER_WIDTH = 2560;
    const RECIPE_COVER_HEIGHT = null;
    const RECIPE_THUMBNAIL_WIDTH = 860;
    const RECIPE_THUMBNAIL_HEIGHT = null;
    protected $imageManager;
    protected $file;
    protected $name;

    public function __construct(ImageManager $imageManager)
    {
        $this->imageManager = $imageManager;

        return $this;
    }

    public function getName(?string $name): string
    {
        $this->name = Str::slug($name) . '-' . $this->getHash(6);

        return $this->name;
    }

    protected function getHash(int $length) : string
    {
        $characterList = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z'];

        shuffle($characterList);

        return implode('', array_slice($characterList, 0, $length));
    }

    public function makeRecipeThumbnail(): bool
    {
        try {
            $image = $this->generateImageType('thumbnail');

            $this->saveAs($image, 'jpg', 'thumbnail');
            $this->saveAs($image, 'webp', 'thumbnail');
        } catch (\Exception $e) {
            print $e->getMessage();
            return false;
        }

        return true;
    }

    public function makeRecipeCover(): bool
    {
        try {
            $image = $this->generateImageType('cover');

            $this->saveAs($image, 'jpg', 'cover');
            $this->saveAs($image, 'webp', 'cover');
        } catch (\Exception $e) {
            print $e->getMessage();
            return false;
        }

        return true;
    }

    public function setFile(UploadedFile $file): self
    {
        $this->file = $file;

        return $this;
    }

    public function keepOriginal(): self
    {
        try {
            $this->file->move(
                $this->getLocalPath(),
                $this->getFullName()
            );
        } catch (\Exception $e) {
            print '.ERROR: ' . $e->getMessage();
            exit;
        }

        return $this;
    }

    protected function getLocalPath(): string
    {
        return Storage::disk(self::STORAGE_DISK_LOCAL)->path('');
    }

    protected function getPublicPath(): string
    {
        return Storage::disk(self::STORAGE_DISK_PUBLIC)->path('');
    }

    protected function getFullName(): string
    {
        return $this->name . '.' . $this->file->extension();
    }

    protected function saveAs(Image $image, string $extension, string $imageType)
    {
        $image
            ->save(
                $this->getPublicPath() . $imageType.'s/' . $this->name . '.' . $extension,
                100
            );
    }

    protected function generateImageType(string $imageType)
    {
        $image = $this->imageManager
            ->make($this->file)
            ->resize(constant('self::RECIPE_' . mb_strtoupper($imageType) . '_WIDTH'), constant('self::RECIPE_' . mb_strtoupper($imageType) . '_HEIGHT'), function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });

        $image = $this->addWatermark($image);

        return $image;
    }

    protected function addWatermark(Image $image): Image
    {
        $watermark = $this->imageManager
            ->make(Storage::disk('logo')->path('') . 'black-logo-no-background.png');
        $watermark->resize(
            $image->width() * 0.25,
            null,
            function ($constraint) {
                $constraint->aspectRatio();
            });

        $image->insert($watermark, 'bottom-right', 10, 10);

        return $image;
    }
}
