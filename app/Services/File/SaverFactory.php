<?php

namespace App\Services\File;

class SaverFactory
{
    const SAVER_NAMESPACE = 'App\Services\File\\';
    protected $saver;

    public function __construct(Saver $saver)
    {
        $this->saver = $saver;
    }

    public function get(string $fileFormat): Saver
    {
        $saverClassName = $this->saverClassName($fileFormat);

        $this->saver->setSaver(
            new $saverClassName()
        );

        return $this->saver;
    }

    protected function saverClassName($fileFormat) : string
    {
        return self::SAVER_NAMESPACE . ucfirst($fileFormat) . 'Saver';
    }
}
