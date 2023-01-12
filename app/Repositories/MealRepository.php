<?php

namespace App\Repositories;

use App\Models\Meal;
use App\Repositories\Interfaces\MealRepositoryInterface;
use Illuminate\Support\Collection;

class MealRepository implements MealRepositoryInterface
{
    public function get(int $id) : Meal
    {
        return Meal::find($id);
    }

    public function create(array $attributes) : Meal
    {
        return Meal::create($attributes);
    }

    public function delete(int $id): void
    {
        Meal::destroy($id);
    }

    public function getForUserBetweenDates(int $userId, string $dateFrom, string $dateTo): Collection
    {
        return Meal::join('diet_plans', 'meals.diet_plan_id', 'diet_plans.id')
            ->where('user_id', $userId)
            ->where('date_on', '>=', $dateFrom)
            ->where('date_on', '<=', $dateTo)
            ->get();
    }
}
