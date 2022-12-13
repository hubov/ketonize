<?php

namespace Database\Factories;

use App\Models\DietMealDivision;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MealEnergyDivision>
 */
class MealEnergyDivisionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'diet_meal_division_id' => DietMealDivision::factory(),
            'tag_id' => Tag::factory(),
            'meal_order' => fake()->randomDigit(),
            'kcal_share' => 25
        ];
    }
}
