<?php

namespace App\Services\Image\Factories;

use App\Http\Traits\VariableClassName;

class ImageFactory
{
    use VariableClassName;

    const IMAGE_NAMESPACE = 'App\Services\Image\Factories';

    public function get(string $imageType)
    {
        $className = $this->getClassName(
            self::IMAGE_NAMESPACE,
            $imageType
        );

        return new $className();
    }
}
