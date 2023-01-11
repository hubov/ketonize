<?php

namespace App\Services\Interfaces;

use App\Models\DietPlan;
use App\Repositories\Interfaces\RecipeRepositoryInterface;

interface AddMealsToDietPlanInterface
{
    public function __construct(RecipeRepositoryInterface $recipeRepository, MealInterface $mealService);
    public function setDietPlan(DietPlan $dietPlan);
    public function setUp();
}
