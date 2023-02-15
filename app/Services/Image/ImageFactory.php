<?php

namespace App\Services\Image;

use App\Http\Traits\VariableClassName;

class ImageFactory
{
    use VariableClassName;

    const IMAGE_NAMESPACE = 'App\Services\Image';

    public function get(string $imageType)
    {
        $className = $this->getClassName(
            self::IMAGE_NAMESPACE,
            $imageType
        );

        return new $className();
    }
}
