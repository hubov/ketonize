<?php

namespace App\Services\Interfaces\Recipe;

use App\Models\Interfaces\RecipeModelInterface;
use App\Repositories\Interfaces\IngredientRepositoryInterface;

interface RelateIngredientsToRecipeInterface
{
    public function __construct(
        IngredientRepositoryInterface $ingredientRepository
    );
    public function setRecipe(RecipeModelInterface $recipe): self;
    public function addIngredient(int $ingredientId, int $amount): self;
    public function sync() : void;
}
