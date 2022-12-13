<?php

namespace Database\Factories;

use App\Models\DietMealDivision;
use App\Models\MealEnergyDivision;
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
            'meals_count' => 4
        ];
    }

    public function configure()
    {
        return $this->afterMaking(function (DietMealDivision $dietMealDivision) {
            //
        })->afterCreating(function (DietMealDivision $dietMealDivision) {
            for ($i = 1; $i <= $dietMealDivision->meals_count; $i++) {
                MealEnergyDivision::factory()->create(['diet_meal_division_id' => $dietMealDivision->id, 'meal_order' => $i]);
            }
        });
    }
}
