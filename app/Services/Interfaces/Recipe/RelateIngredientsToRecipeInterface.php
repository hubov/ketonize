<?php

namespace App\Services\Interfaces\Recipe;

use App\Models\Interfaces\RecipeModelInterface;
use App\Repositories\Interfaces\CustomIngredientRepositoryInterface;
use App\Repositories\Interfaces\IngredientRepositoryInterface;

interface RelateIngredientsToRecipeInterface
{
    public function __construct(
        IngredientRepositoryInterface $ingredientRepository,
        CustomIngredientRepositoryInterface $customIngredientRepository,
    );
    public function setRecipe(RecipeModelInterface $recipe): self;
    public function setUser(int $userId): self;
    public function addIngredient(int $ingredientId, int $amount): self;
    public function addIngredientByName(string $ingredientName, int $amount, int $unitId): self;
    public function sync() : void;
}
