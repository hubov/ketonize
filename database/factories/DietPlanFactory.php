<?php

namespace Database\Factories;

use App\Models\DietPlan;
use App\Models\Meal;
use App\Models\Recipe;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DietPlan>
 */
class DietPlanFactory extends Factory
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
            'date_on' => '2022-09-30'
        ];
    }

    /**
     * Set up the model's config.
     *
     * @return static
     */
    public function configure()
    {
        return $this->afterMaking(function (DietPlan $dietPlan) {
            //
        })->afterCreating(function (DietPlan $dietPlan) {
            Meal::factory()
                ->for($dietPlan)
                ->create(['meal' => 1]);
        });
    }
}
