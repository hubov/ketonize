<?php

namespace App\Repositories;

use App\Models\Meal;
use App\Repositories\Interfaces\MealRepositoryInterface;

class MealRepository implements MealRepositoryInterface
{
    public function get(int $id) : Meal
    {
        return Meal::find($id)->first();
    }

    public function create(array $attributes) : Meal
    {
        return Meal::create($attributes);
    }

    public function delete(int $id): void
    {
        Meal::destroy($id);
    }
}
