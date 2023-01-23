<?php

namespace App\Services\Interfaces\Recipe;

use App\Repositories\Interfaces\IngredientRepositoryInterface;
use App\Repositories\Interfaces\RecipeRepositoryInterface;

interface RelateIngredientsToRecipeInterface
{
    public function __construct(RecipeRepositoryInterface $recipeRepository, IngredientRepositoryInterface $ingredientRepository);
    public function setRecipe(int $recipeId);
    public function addIngredient(int $ingredientId, int $amount);
    public function sync() : void;
}