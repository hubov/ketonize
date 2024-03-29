<?php

namespace App\Repositories\Interfaces;

use App\Models\Meal;
use Illuminate\Support\Collection;

interface MealRepositoryInterface
{
    public function get(int $id) : Meal;
    public function getByMeal(int $dietPlanId, int $meal) : Collection;
    public function create(array $attributes) : Meal;
    public function delete(int $id) : bool;
    public function getForUserBetweenDates(int $userId, string $dateFrom, string $dateTo) : Collection;
}
