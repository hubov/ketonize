<?php

namespace App\Services;

use App\Repositories\Interfaces\IngredientCategoryRepositoryInterface;
use App\Services\Interfaces\IngredientCategoryInterface;

class IngredientCategoryService implements IngredientCategoryInterface
{
    protected $ingredientCategoryRepository;

    public function __construct(IngredientCategoryRepositoryInterface $ingredientCategoryRepository)
    {
        $this->ingredientCategoryRepository = $ingredientCategoryRepository;
    }

    public function getAllByName() : array
    {
        $results = [];

        $rawResults = $this->ingredientCategoryRepository
            ->getAll()
            ->sortBy('name');

        foreach ($rawResults as $result) {
            $results[$result->name] = $result;
        }

        return $results;
    }
}
