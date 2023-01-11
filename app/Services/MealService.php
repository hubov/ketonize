<?php

namespace App\Services;

use App\Models\DietPlan;
use App\Models\Meal;
use App\Models\Recipe;
use App\Repositories\Interfaces\MealRepositoryInterface;
use App\Repositories\Interfaces\RecipeRepositoryInterface;
use App\Services\Interfaces\MealInterface;

class MealService implements MealInterface
{
    protected $mealRepository;
    protected $recipeRepository;
    protected $dietPlan;
    protected $userDiet;
    protected $mealsDivision;
    protected $chosenRecipes = [];

    public function __construct(RecipeRepositoryInterface $recipeRepository, MealRepositoryInterface $mealRepository)
    {
        $this->mealRepository = $mealRepository;
        $this->recipeRepository = $recipeRepository;
    }

    public function setDietPlan(DietPlan $dietPlan)
    {
        $this->dietPlan = $dietPlan;
    }

    public function add(Recipe $recipe, int $kcal, int $mealOrder) : Meal
    {
        return $this->mealRepository->create([
            'diet_plan_id' => $this->dietPlan->id,
            'modifier' => round($kcal / $recipe->kcal * 100),
            'recipe_id' => $recipe->id,
            'meal' => $mealOrder
        ]);
    }
}
