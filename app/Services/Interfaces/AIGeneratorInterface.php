<?php

namespace App\Services\Interfaces;

interface AIGeneratorInterface
{
    public function prompt(string $text): self;
    public function return(): string;
}
