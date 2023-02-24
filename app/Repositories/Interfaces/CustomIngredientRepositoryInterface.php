<?php

namespace App\Repositories\Interfaces;

use App\Models\CustomIngredient;

interface CustomIngredientRepositoryInterface
{
    public function getOrCreateForUserByName(int $userId, array $attributes) : CustomIngredient;
}
