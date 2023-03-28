<?php

namespace App\Repositories;

use App\Models\CustomIngredient;
use App\Repositories\Interfaces\CustomIngredientRepositoryInterface;

class CustomIngredientRepository implements CustomIngredientRepositoryInterface
{
    public function getOrCreateForUserByName(int $userId, array $attributes): CustomIngredient
    {
        return CustomIngredient::firstOrCreate(
            [
                'name' => $attributes['name'],
                'user_id' => $userId,
                'unit_id' => $attributes['unit']
            ]
        );
    }
}
