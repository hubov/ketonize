<?php

namespace Tests\Unit;

use App\Models\DietMealDivision;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DietMealDivisionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function tag_dietMealDivision_relation_existence()
    {
        $this->assertTrue(method_exists(DietMealDivision::class, 'tag'));
    }

    /** @test */
    public function mealEnergyDivisions_dietMealDivision_relation_existence()
    {
        $this->assertTrue(method_exists(DietMealDivision::class, 'mealEnergyDivisions'));
    }

    /** @test */
    public function getMeals_method()
    {
        $dietMealDivision = new DietMealDivision();

        $this->assertEquals($dietMealDivision->mealEnergyDivisions(), $dietMealDivision->getMeals());
    }

    /** @test */
    public function getting_mealsTags_by_meal_count()
    {
        $dietMealDivision = DietMealDivision::factory()->create(['meals_count' => 4]);

        $tags = $dietMealDivision->mealsTags();

        $this->assertIsArray($tags);
        $this->assertCount(4, $tags);
    }
}
