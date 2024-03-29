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

    public function getByMeal(int $dietPlanId, int $meal): Collection
    {
        return Meal::where('diet_plan_id', $dietPlanId)
            ->where('meal', $meal)
            ->get();
    }

    public function create(array $attributes) : Meal
    {
        return Meal::create($attributes);
    }

    public function delete(int $id): bool
    {
        return Meal::destroy($id);
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
