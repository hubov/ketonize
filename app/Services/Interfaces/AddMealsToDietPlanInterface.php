<?php

namespace App\Services\Interfaces;

use App\Models\DietPlan;
use App\Services\Interfaces\Recipe\SelectRecipeForDietInterface;

interface AddMealsToDietPlanInterface
{
    public function __construct(MealInterface $mealService, SelectRecipeForDietInterface $selectRecipeForDietService);
    public function setDietPlan(DietPlan $dietPlan);
    public function setUp();
}
