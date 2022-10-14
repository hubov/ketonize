<?php

namespace Tests\Unit;

use App\Http\Controllers\DietPlanController;
use App\Models\DietPlan;
use App\Models\User;
use Database\Factories\DietPlanFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\MockInterface;
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

        $this->assertEquals(['prev' => '2022-09-29', 'next' => '2022-10-01'], ['prev' => $dietPlanController->dates['prev'], 'next' => $dietPlanController->dates['next']]);
    }
}