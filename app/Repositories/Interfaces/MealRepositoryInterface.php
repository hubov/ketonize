<?php

namespace App\Repositories\Interfaces;

use App\Models\Meal;

interface MealRepositoryInterface
{
    public function get(int $id) : Meal;
    public function create(array $attributes) : Meal;
    public function delete(int $id) : void;
}
