<?php

namespace App\Services\Interfaces;

use App\Repositories\Interfaces\IngredientRepositoryInterface;
use Illuminate\Support\Collection;

interface IngredientInterface
{
    public function __construct(IngredientRepositoryInterface $ingredientRepository);
    public function getSorted(string ...$columns) : Collection;
    public function relatedRecipes(int $id) : Collection;
}
