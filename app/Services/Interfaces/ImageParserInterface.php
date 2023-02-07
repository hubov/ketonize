<?php

namespace App\Services\Interfaces;

interface ImageParserInterface
{
    public function getName(?string $name) : string;
}
