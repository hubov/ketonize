<?php

namespace App\Services;

use App\Services\Interfaces\ImageParserInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageParser implements ImageParserInterface
{
    const STORAGE_DISK_LOCAL = 'local_recipe_images';
    const STORAGE_DISK_PUBLIC = 'public_recipe_images';
    protected $file;
    protected $name;

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
}
