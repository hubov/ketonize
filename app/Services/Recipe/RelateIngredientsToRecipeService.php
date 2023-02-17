<?php

namespace App\Services\Recipe;

use App\Models\Recipe;
use App\Repositories\Interfaces\IngredientRepositoryInterface;
use App\Services\Interfaces\Recipe\RelateIngredientsToRecipeInterface;

class RelateIngredientsToRecipeService implements RelateIngredientsToRecipeInterface
{
    protected $ingredientRepository;
    protected $recipe;
    protected $ingredients = [];

    public function __construct(
        IngredientRepositoryInterface $ingredientRepository
    ) {
        $this->ingredientRepository = $ingredientRepository;

        return $this;
    }

    public function setRecipe(Recipe $recipe): self
    {
        $this->recipe = $recipe;

        return $this;
    }

    public function addIngredient(int $ingredientId, int $amount): self
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
