<?php

namespace Tests\Unit;

use App\Http\Controllers\DietPlanController;
use App\Models\DietPlan;
use App\Models\Recipe;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DietPlanControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic unit test example.
     *
     * @return void
     */

    public function test_dates_prev_next()
    {
        $dietPlanController = new DietPlanController;
        $dietPlanController->setDate('2022-09-30');

        $this->assertEquals([
            'prev' => '2022-09-29',
            'next' => '2022-10-01'
        ], [
            'prev' => $dietPlanController->dates['prev'],
            'next' => $dietPlanController->dates['next']
        ]);
    }

    public function test_getMeals_method()
    {
        $dietPlan = DietPlan::factory()->create();
        $dietPlanController = new DietPlanController();
        $this->actingAs(User::find($dietPlan->user_id));
        $dietPlanController->setDate($dietPlan->date_on);

        $dietPlanController->getMeals();

        $this->assertCount(1, $dietPlanController->meals);
    }

    public function test_sumUp_method()
    {
        $dietPlanController = new DietPlanController;
        $dietPlanController->meals = [
            1 => DietPlan::factory()->for(Recipe::factory()->state([
                'protein' => 100,
                'fat' => 100,
                'carbohydrate' => 100,
                'kcal' => 1000,
                'preparation_time' => 60,
                'cooking_time' => 60
            ]))->create([
                'modifier' => 50
            ]),
            2 => DietPlan::factory()->for(Recipe::factory()->state([
                'protein' => 100,
                'fat' => 100,
                'carbohydrate' => 100,
                'kcal' => 1500,
                'preparation_time' => 20,
                'cooking_time' => 0
            ]))->create([
                'modifier' => 100
            ])
        ];

        $result = $dietPlanController->sumUp();

        $this->assertEquals([
            'totalProtein' => 150,
            'totalFat' => 150,
            'totalCarbohydrate' => 150,
            'totalKcal' => 2000,
            'totalPreparation' => 80,
            'totalTime' => 140,
            'shareProtein' => 33,
            'shareFat' => 33,
            'shareCarbohydrate' => 33
        ], [
            'totalProtein' => $result['totalProtein'],
            'totalFat' => $result['totalFat'],
            'totalCarbohydrate' => $result['totalCarbohydrate'],
            'totalKcal' => $result['totalKcal'],
            'totalPreparation' => $result['totalPreparation'],
            'totalTime' => $result['totalTime'],
            'shareProtein' => $result['shareProtein'],
            'shareFat' => $result['shareFat'],
            'shareCarbohydrate' => $result['shareCarbohydrate']
        ]);
    }
}
