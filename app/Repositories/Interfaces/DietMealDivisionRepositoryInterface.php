<?php

namespace App\Repositories\Interfaces;

use App\Models\DietMealDivision;

interface DietMealDivisionRepositoryInterface
{
    public function get(int $id) : DietMealDivision;
    public function getByMealsCount(int $mealsCount) : DietMealDivision;
}
