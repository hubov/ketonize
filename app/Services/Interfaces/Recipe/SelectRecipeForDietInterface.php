<?php

namespace App\Services\Interfaces\Recipe;

use App\Models\Recipe;
use App\Models\UserDiet;
use App\Repositories\Interfaces\RecipeRepositoryInterface;

interface SelectRecipeForDietInterface
{
    public function __construct(RecipeRepositoryInterface $recipeRepository);
    public function setTags(array $tags): self;
    public function setUserDiet(UserDiet $userDiet): self;
    public function ignoreRecipes(array $ignoreRecipeIds): self;
    public function get() : Recipe;
}
