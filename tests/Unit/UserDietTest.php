<?php

namespace Tests\Unit;

use App\Models\DietMealDivision;
use App\Models\UserDiet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserDietTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_user_userDiet_relation_existence()
    {
        $this->assertTrue(method_exists(UserDiet::class, 'user'));
    }

    public function test_diet_userDiet_relation_existence()
    {
        $this->assertTrue(method_exists(UserDiet::class, 'diet'));
    }

    public function test_mealsDivision_method_in_user_diet()
    {
        for ($i = 0; $i < 4; $i++) {
            DietMealDivision::factory()->state([
                'meals_count' => 4,
                'kcal_share' => 30,
                'meal_order' => $i
            ])->create();
        }
        DietMealDivision::factory()->state([
            'meals_count' => 4,
            'kcal_share' => 10,
            'meal_order' => 4
        ])->create();

        $userDiet = UserDiet::factory()->create([
            'kcal' => 1800
        ]);
        $userDiet->getMeals();
        $result = $userDiet->mealsDivision();

        $this->assertEquals([540, 180], [$result[0]['kcal'], $result[4]['kcal']]);
    }
}
