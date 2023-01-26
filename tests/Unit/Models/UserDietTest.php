<?php

namespace Tests\Unit\Models;

use App\Models\DietMealDivision;
use App\Models\Tag;
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
    public function dietMealDivision_userDiet_relation_existence()
    {
        $this->assertTrue(method_exists(UserDiet::class, 'dietMealDivision'));
    }

    /** @test */
    public function getMacros_method_returns_macros_sum()
    {
        $userDiet = new UserDiet();
        $userDiet->protein = 5;
        $userDiet->fat = 6;
        $userDiet->carbohydrate = 7;

        $this->assertEquals(18, $userDiet->getMacros());
    }

    /** @test */
    public function getProteinRatio_method_returns_protein_ratio()
    {
        $userDiet = new UserDiet();
        $userDiet->protein = 10;
        $userDiet->fat = 50;
        $userDiet->carbohydrate = 40;

        $this->assertEquals(10, $userDiet->getProteinRatio());
    }

    /** @test */
    public function getCarbohydrateRatio_method_returns_carbohydrate_ratio()
    {
        $userDiet = new UserDiet();
        $userDiet->protein = 10;
        $userDiet->fat = 50;
        $userDiet->carbohydrate = 40;

        $this->assertEquals(40, $userDiet->getCarbohydrateRatio());
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

    /** @test */
    public function it_returns_meals_division_tags()
    {
        $tag1 = new Tag();
        $tag1->id = 1;
        $tag2 = new Tag();
        $tag2->id = 2;
        $meal1 = new \stdClass();
        $meal1->tag = $tag1;
        $meal2 = new \stdClass();
        $meal2->tag = $tag2;

        $dietMealDivision = new DietMealDivision();
        $dietMealDivision->mealEnergyDivisions = collect([$meal1, $meal2]);

        $userDiet = new UserDiet();
        $userDiet->dietMealDivision = $dietMealDivision;

        $this->assertEquals(
            [1, 2],
            $userDiet->getMealsTags()
        );
    }
}
