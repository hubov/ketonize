<?php

namespace App\Services\Interfaces;

use App\Repositories\Interfaces\IngredientCategoryRepositoryInterface;

interface IngredientCategoryInterface
{
    public function __construct(IngredientCategoryRepositoryInterface $ingredientCategoryRepository);
    public function getAllByName() : array;
}
