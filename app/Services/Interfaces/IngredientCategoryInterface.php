<?php

namespace App\Services\Interfaces;

use App\Repositories\Interfaces\IngredientCategoryRepositoryInterface;
use Illuminate\Support\Collection;

interface IngredientCategoryInterface
{
    public function __construct(IngredientCategoryRepositoryInterface $ingredientCategoryRepository);
    public function getSorted(array $columns) : Collection;
}
