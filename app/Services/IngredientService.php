<?php

namespace App\Services;

use App\Repositories\Interfaces\IngredientRepositoryInterface;
use App\Services\Interfaces\IngredientInterface;
use Illuminate\Support\Collection;

class IngredientService implements IngredientInterface
{
    protected $ingredientRepository;

    public function __construct(IngredientRepositoryInterface $ingredientRepository)
    {
        $this->ingredientRepository = $ingredientRepository;

        return $this;
    }

    public function getSorted(string ...$columns) : Collection
    {
        $ingredients = $this->ingredientRepository->getAll();

        foreach ($columns as $column) {
            $ingredients->sortBy($column);
        }

        return $ingredients;
    }

    public function relatedRecipes (int $id) : Collection
    {
        $recipes = [];
        $ingredient = $this->ingredientRepository->get($id);

        if (count($ingredient->recipes) > 0) {
            foreach ($ingredient->recipes as $recipe) {
                $recipes[] = $recipe->slug;
            }
        }

        return collect($recipes);
    }
}
