<?php

namespace Database\Factories;

use App\Models\DietPlan;
use App\Models\Recipe;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Meal>
 */
class MealFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'diet_plan_id' => DietPlan::factory(),
            'recipe_id' => Recipe::factory(),
            'meal' => fake()->numberBetween(1, 4),
            'modifier' => '100'
        ];
    }
}
