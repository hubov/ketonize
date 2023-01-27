<?php

namespace App\Repositories;

use App\Models\DietMealDivision;
use App\Repositories\Interfaces\DietMealDivisionRepositoryInterface;

class DietMealDivisionRepository implements DietMealDivisionRepositoryInterface
{
    public function get(int $id) : DietMealDivision
    {
        return DietMealDivision::find($id);
    }

    public function getByMealsCount(int $mealsCount) : DietMealDivision
    {
        return DietMealDivision::where('meals_count', $mealsCount)->firstOrFail();
    }
}
