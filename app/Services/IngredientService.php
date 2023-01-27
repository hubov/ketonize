<?php

namespace App\Services;

use App\Repositories\Interfaces\IngredientRepositoryInterface;
use App\Services\Interfaces\IngredientInterface;

class IngredientService implements IngredientInterface
{
    protected $ingredientRepository;

    public function __construct(IngredientRepositoryInterface $ingredientRepository)
    {
        $this->ingredientRepository = $ingredientRepository;

        return $this;
    }

    public function relatedRecipes (int $id) : array
    {
        $recipes = [];
        $ingredient = $this->ingredientRepository->get($id);

        if (count($ingredient->recipes) > 0) {
            foreach ($ingredient->recipes as $recipe) {
                $recipes[] = $recipe->slug;
            }
        }

        return $recipes;
    }
}
