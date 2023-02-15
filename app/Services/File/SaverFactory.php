<?php

namespace App\Services\File;

use App\Http\Traits\VariableClassName;

class SaverFactory
{
    use VariableClassName;

    const SAVER_NAMESPACE = 'App\Services\File';
    protected $saver;

    public function __construct(Saver $saver)
    {
        $this->saver = $saver;
    }

    public function get(string $fileFormat): Saver
    {
        $saverClassName = $this->getClassName(
            self::SAVER_NAMESPACE,
            $fileFormat . 'Saver'
        );

        $this->saver->setSaver(
            new $saverClassName()
        );

        return $this->saver;
    }
}
