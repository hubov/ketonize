<?php

namespace Database\Factories;

use App\Models\IngredientCategory;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ingredient>
 */
class IngredientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => fake()->word(),
            'protein' => 15,
            'fat' => 50,
            'carbohydrate' => 5,
            'kcal' => 60,
            'ingredient_category_id' => IngredientCategory::factory(),
            'unit_id' => Unit::factory()
         ];

    }
}
