<?php

namespace App\Repositories;

use App\Models\MealEnergyDivision;
use App\Repositories\Interfaces\MealEnergyDivisionRepositoryInterface;

class MealEnergyDivisionRepository implements MealEnergyDivisionRepositoryInterface
{
    public function getByDietMealDivision(int $dietMealDivisionId) : array
    {
        $mealsNames = [];

        $results = MealEnergyDivision::select('meal_order', 'tags.name')
            ->join('tags', 'tags.id', 'meal_energy_divisions.tag_id')
            ->where('diet_meal_division_id', $dietMealDivisionId)
            ->get();

        foreach ($results as $result) {
            $mealsNames[$result['meal_order']] = mb_convert_case($result['name'], MB_CASE_TITLE);
        }

        return $mealsNames;
    }
}
