<?php

namespace Tests\Unit;

use App\Models\DietMealDivision;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DietMealDivisionTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_tag_dietMealDivision_relation_existence()
    {
        $this->assertTrue(method_exists(DietMealDivision::class, 'tag'));
    }

    public function test_mealEnergyDivisions_dietMealDivision_relation_existence()
    {
        $this->assertTrue(method_exists(DietMealDivision::class, 'mealEnergyDivisions'));
    }

    public function test_getMeals_method()
    {
        $dietMealDivision = new DietMealDivision();

        $this->assertEquals($dietMealDivision->mealEnergyDivisions(), $dietMealDivision->getMeals());
    }

    public function test_getting_mealsTags_by_meal_count()
    {
        $dietMealDivision = DietMealDivision::factory()->create(['meals_count' => 4]);

        $tags = $dietMealDivision->mealsTags();

        $this->assertIsArray($tags);
        $this->assertCount(4, $tags);
    }
}
