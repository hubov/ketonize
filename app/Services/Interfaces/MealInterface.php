<?php

namespace App\Services\Interfaces;

use App\Models\DietPlan;
use App\Models\Meal;
use App\Models\Recipe;
use App\Repositories\Interfaces\MealRepositoryInterface;
use App\Repositories\Interfaces\RecipeRepositoryInterface;

interface MealInterface
{
    public function __construct(
        RecipeRepositoryInterface $recipeRepository,
        MealRepositoryInterface $mealRepository
    );
    public function setDietPlan(DietPlan $dietPlan): self;
    public function add(Recipe $recipe, int $kcal, int $mealOrder) : Meal;
    public function change(int $mealOrder, string $recipeSlug) : Meal;
    public function delete(int $mealId) : bool;
    public function calculateModifier(int $mealKcal, int $recipeKcal) : int;
}
