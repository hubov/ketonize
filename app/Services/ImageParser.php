<?php

namespace App\Services;

use App\Services\Interfaces\ImageParserInterface;
use Illuminate\Support\Str;

class ImageParser implements ImageParserInterface
{
    public function getName(?string $name): string
    {
        $result = Str::slug($name);

        return $result . '-' . $this->getHash(6);
    }

    protected function getHash(int $length) : string
    {
        $characterList = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z'];

        shuffle($characterList);

        return implode('', array_slice($characterList, 0, $length));
    }
}
