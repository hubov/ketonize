<?php

namespace App\Services\Interfaces\Recipe;

use App\Models\Recipe;
use App\Repositories\Interfaces\IngredientRepositoryInterface;
use App\Repositories\Interfaces\RecipeRepositoryInterface;
use App\Repositories\Interfaces\TagRepositoryInterface;
use App\Services\Interfaces\RelateIngredientsToRecipeInterface;

interface RecipeCreateOrUpdateInterface
{
    public function __construct(RecipeRepositoryInterface $profileRepository, IngredientRepositoryInterface $ingredientRepository, TagRepositoryInterface $tagRepository, RelateIngredientsToRecipeInterface $relateIngredientsToRecipe);
    public function perform(array $attributes, string $slug) : Recipe;
}