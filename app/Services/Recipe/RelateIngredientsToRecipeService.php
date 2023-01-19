<?php

namespace App\Services\Recipe;

use App\Repositories\Interfaces\IngredientRepositoryInterface;
use App\Repositories\Interfaces\RecipeRepositoryInterface;
use App\Services\Interfaces\Recipe\RelateIngredientsToRecipeInterface;

class RelateIngredientsToRecipeService implements RelateIngredientsToRecipeInterface
{
    protected $recipeRepository;
    protected $ingredientRepository;
    protected $recipe;
    protected $ingredients = [];

    public function __construct(RecipeRepositoryInterface $recipeRepository, IngredientRepositoryInterface $ingredientRepository)
    {
        $this->recipeRepository = $recipeRepository;
        $this->ingredientRepository = $ingredientRepository;

        return $this;
    }

    public function setRecipe(int $recipeId)
    {
        $this->recipe = $this->recipeRepository->get($recipeId);

        return $this;
    }

    public function addIngredient(int $ingredientId, int $amount)
    {
        if (isset($this->ingredients[$ingredientId])) {
            $this->ingredients[$ingredientId]['amount'] += $amount;
        } else {
            $this->ingredients[$ingredientId] = [
                'amount' => $amount
            ];
        }

        return $this;
    }

    public function sync() : void
    {
        $this->recipe->ingredients()->sync($this->ingredients);
    }
}
