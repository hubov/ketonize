<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Recipe>
 */
class RecipeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => fake()->name(),
            'slug' => fake()->regexify('[a-z\-]{10}'),
            'protein' => fake()->numberBetween(20, 30),
            'fat' => fake()->numberBetween(30, 60),
            'carbohydrate' => fake()->numberBetween(5, 15),
            'kcal' => fake()->numberBetween(250, 700),
            'protein_ratio' => 20,
            'fat_ratio' => 77,
            'carbohydrate_ratio' => 8,
            'description' => fake()->sentence(),
            'image' => 'default',
            'preparation_time' => fake()->numberBetween(5, 20),
            'cooking_time' => fake()->numberBetween(10, 60),
            'total_time' => fake()->numberBetween(5, 80),
        ];
    }
}
