<?php

namespace Tests\Unit;

use App\Models\MealEnergyDivision;
use PHPUnit\Framework\TestCase;

class MealEnergyDivisionTest extends TestCase
{
    /** @test */
    public function tag_mealEnergyDivision_relation_existence()
    {
        $this->assertTrue(method_exists(MealEnergyDivision::class, 'tag'));
    }

    /** @test */
    public function dietMealDivision_mealEnergyDivision_relation_existence()
    {
        $this->assertTrue(method_exists(MealEnergyDivision::class, 'dietMealDivision'));
    }
}
