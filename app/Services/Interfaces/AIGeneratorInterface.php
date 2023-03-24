<?php

namespace App\Services\Interfaces;

interface AIGeneratorInterface
{
    public function settings(array $attributes): self;
    public function execute(): self;
    public function return(): string|array;
}
