<?php

namespace Tests\Unit;

use App\Models\DietMealDivision;
use App\Models\MealEnergyDivision;
use App\Models\UserDiet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserDietTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_userDiet_relation_existence()
    {
        $this->assertTrue(method_exists(UserDiet::class, 'user'));
    }

    /** @test */
    public function diet_userDiet_relation_existence()
    {
        $this->assertTrue(method_exists(UserDiet::class, 'diet'));
    }

    /** @test */
    public function mealsDivision_method_in_user_diet()
    {
        $dietMealDiv = DietMealDivision::factory()->create(['meals_count' => 4]);

        $i = 1;
        $shares = [30, 30, 30, 10];

        foreach ($dietMealDiv->getMeals as $meal) {
            $meal->kcal_share = $shares[$i - 1];
            $meal->meal_order = $i;
            $meal->save();
            $i++;
        }

        $userDiet = UserDiet::factory()->create([
            'diet_meal_division_id' => $dietMealDiv->id,
            'kcal' => 1800
        ]);

        $result = $userDiet->mealsDivision();

        $this->assertEquals([540, 180], [$result[1]['kcal'], $result[4]['kcal']]);
    }
}
