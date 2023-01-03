<?php

namespace App\Services;

use App\Repositories\Interfaces\IngredientCategoryRepositoryInterface;
use App\Services\Interfaces\IngredientCategoryInterface;
use Illuminate\Support\Collection;

class IngredientCategoryService implements IngredientCategoryInterface
{
    protected $ingredientCategoryRepository;

    public function __construct(IngredientCategoryRepositoryInterface $ingredientCategoryRepository)
    {
        $this->ingredientCategoryRepository = $ingredientCategoryRepository;

        return $this;
    }

    public function getSorted(array $columns) : Collection
    {
        $categories = $this->ingredientCategoryRepository->getAll();
        $categories->sortBy($columns);

        return $categories;
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
