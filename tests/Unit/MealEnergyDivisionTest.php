<?php

namespace Tests\Unit;

use App\Models\MealEnergyDivision;
use PHPUnit\Framework\TestCase;

class MealEnergyDivisionTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_tag_mealEnergyDivision_relation_existence()
    {
        $this->assertTrue(method_exists(MealEnergyDivision::class, 'tag'));
    }

    public function test_dietMealDivision_mealEnergyDivision_relation_existence()
    {
        $this->assertTrue(method_exists(MealEnergyDivision::class, 'dietMealDivision'));
    }
}
