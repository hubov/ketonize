<?php

namespace Database\Factories;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DietMealDivision>
 */
class DietMealDivisionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'meals_count' => 4,
            'meal_order' => fake()->randomNumber(),
            'tag_id' => Tag::factory(),
            'kcal_share' => 33
        ];
    }
}
