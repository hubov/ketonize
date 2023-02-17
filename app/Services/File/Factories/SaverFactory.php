<?php

namespace App\Services\File\Factories;

use App\Http\Traits\VariableClassName;
use App\Services\Interfaces\File\FileSaver;

class SaverFactory
{
    use VariableClassName;

    const SAVER_NAMESPACE = 'App\Services\File\Factories';

    public function get(string $fileFormat): FileSaver
    {
        $saverClassName = $this->getClassName(
            self::SAVER_NAMESPACE,
            $fileFormat . 'Saver'
        );

        return new $saverClassName();
    }
}
