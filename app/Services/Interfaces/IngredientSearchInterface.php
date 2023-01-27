<?php

namespace App\Services\Interfaces;

use App\Repositories\Interfaces\IngredientRepositoryInterface;

interface IngredientSearchInterface
{
    public function __construct(IngredientRepositoryInterface $ingredientRepository);
    public function query(string $query): self;
    public function limit(int $limit): self;
    public function return() : array;
}
