<?php

namespace Database\Factories;

use App\Models\Diet;
use App\Models\DietMealDivision;
use App\Models\Profile;
use App\Models\User;
use App\Models\UserDiet;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Set up the model's config.
     *
     * @return static
     */
    public function configure()
    {
        return $this->afterMaking(function (User $user) {
            //
        })->afterCreating(function (User $user) {
            Profile::factory()
                ->for($user)
                ->create();
            UserDiet::factory()
                ->for($user)
                ->for(Diet::factory())
                ->for(DietMealDivision::factory())
                ->create();
//            DietMealDivision::factory()->create(['meals_count' => 4]);
        });
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return static
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
