<?php

namespace App\Repositories\Interfaces;

interface MealEnergyDivisionRepositoryInterface
{
    public function getByDietMealDivision(int $dietMealDivisionId) : array;
}
