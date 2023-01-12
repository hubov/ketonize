<?php

namespace App\Services\Interfaces\Recipe;

use App\Models\Recipe;
use App\Models\UserDiet;
use App\Repositories\Interfaces\RecipeRepositoryInterface;

interface SelectRecipeForDietInterface
{
    public function __construct(RecipeRepositoryInterface $recipeRepository);
    public function setTags(array $tags);
    public function setUserDiet(UserDiet $userDiet);
    public function ignoreRecipes(array $ignoreRecipeIds);
    public function get() : Recipe;
}
