<?php

namespace Tests\Unit;

use App\Models\DietPlan;
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

    public function test_user_and_user_diet_relation_existence()
    {
        $this->assertTrue(method_exists(DietPlan::class, 'user'));
    }

    public function test_meal_diet_plan_relation_existence()
    {
        $this->assertTrue(method_exists(DietPlan::class, 'meals'));
    }
}
