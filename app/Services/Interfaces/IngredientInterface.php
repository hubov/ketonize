<?php

namespace App\Services\Interfaces;

use App\Repositories\Interfaces\IngredientRepositoryInterface;

interface IngredientInterface
{
    public function __construct(IngredientRepositoryInterface $ingredientRepository);
    public function relatedRecipes(int $id) : array;
    public function delete(int $ingredientId) : bool;
}
