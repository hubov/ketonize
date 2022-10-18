<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Profile>
 */
class ProfileFactory extends Factory
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
            'height' => 170,
            'weight' => 70,
            'target_weight' => 70,
            'gender' => 1,
            'diet_target' => 2,
            'basic_activity' => 1,
            'sport_activity' => 3,
            'birthday' => '2000-01-01'
        ];
    }
}
