<?php

namespace Database\Factories;

use App\Models\Diet;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserDiet>
 */
class UserDietFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'diet_id' => Diet::factory(),
            'meals_count' => 4,
            'kcal' => 1800,
            'protein' => 15,
            'fat' => 77,
            'carbohydrate' => 8,
        ];
    }
}
