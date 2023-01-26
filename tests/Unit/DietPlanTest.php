<?php

namespace Tests\Unit;

use App\Models\DietPlan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DietPlanTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_and_user_diet_relation_existence()
    {
        $this->assertTrue(method_exists(DietPlan::class, 'user'));
    }

    /** @test */
    public function meal_diet_plan_relation_existence()
    {
        $this->assertTrue(method_exists(DietPlan::class, 'meals'));
    }
}
