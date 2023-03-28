<?php

namespace App\Services\Recipe;

use App\Http\Traits\UniversalIngredientPicker;
use App\Models\Interfaces\RecipeModelInterface;
use App\Repositories\Interfaces\IngredientRepositoryInterface;
use App\Services\Interfaces\Recipe\RelateIngredientsToRecipeInterface;

class RelateIngredientsToRecipeService implements RelateIngredientsToRecipeInterface
{
    use UniversalIngredientPicker;

    protected $ingredientRepository;
    protected $recipe;
    protected $ingredients = [];
    protected $customIngredients = [];

    public function __construct(
        IngredientRepositoryInterface $ingredientRepository
    ) {
        $this->ingredientRepository = $ingredientRepository;

        return $this;
    }

    public function setRecipe(RecipeModelInterface $recipe): self
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

    public function addIngredientByName(string $ingredientName, int $amount): self
    {
        // ...
    }

    public function sync() : void
    {
        $this->recipe->ingredients()->sync($this->ingredients);
    }
}
