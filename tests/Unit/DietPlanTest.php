<?php

namespace Tests\Unit;

use App\Models\DietPlan;
use App\Models\Recipe;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DietPlanTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_recipe_user_diet_relation_existence()
    {
        $this->assertTrue(method_exists(DietPlan::class, 'recipe'));
    }

    public function test_user_and_user_diet_relation_existence()
    {
        $this->assertTrue(method_exists(DietPlan::class, 'user'));
    }

    public function test_scale_method_in_diet_plan()
    {
        $recipe = Recipe::factory()->create([
            'protein' => 100,
            'fat' => 80,
            'carbohydrate' => 60,
            'kcal' => 1000,
        ]);
        $dietPlan = DietPlan::factory()->create([
            'modifier' => 80,
            'recipe_id' => $recipe->id
        ]);
        $dietPlan->scale();

        $this->assertEquals([
            'protein' => 80,
            'fat'  => 64,
            'carbohydrate' => 48,
            'kcal' => 800
        ],
        [
            'protein' => $dietPlan->recipe->protein,
            'fat' => $dietPlan->recipe->fat,
            'carbohydrate' => $dietPlan->recipe->carbohydrate,
            'kcal' => $dietPlan->recipe->kcal,
        ]);
    }

    public function test_shares_method_in_diet_plan()
    {
        $recipe = Recipe::factory()->create([
            'protein' => 100,
            'fat' => 80,
            'carbohydrate' => 60,
            'kcal' => 1000,
        ]);
        $dietPlan = DietPlan::factory()->create([
            'modifier' => 80,
            'recipe_id' => $recipe->id
        ]);
        $dietPlan->shares();

        $this->assertEquals([
            'shareProtein' => 42,
            'shareFat'  => 33,
            'shareCarbohydrate' => 25,
        ],
            [
                'shareProtein' => $dietPlan->shareProtein,
                'shareFat' => $dietPlan->shareFat,
                'shareCarbohydrate' => $dietPlan->shareCarbohydrate,
            ]);
    }
}
