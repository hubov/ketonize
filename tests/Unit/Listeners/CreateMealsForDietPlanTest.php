<?php

namespace Tests\Unit\Listeners;

use App\Events\DietPlanCreated;
use App\Listeners\CreateMealsForDietPlan;
use App\Models\DietPlan;
use App\Services\Interfaces\AddMealsToDietPlanInterface;
use PHPUnit\Framework\TestCase;

class CreateMealsForDietPlanTest extends TestCase
{
    /** @test */
    public function it_updates_all_diet_plans()
    {
        $addMealsToDietPlanService = $this->createMock(AddMealsToDietPlanInterface::class);
        $dietPlanCreatedEvent = $this->createStub(DietPlanCreated::class);
        $dietPlanCreatedEvent->dietPlan = new DietPlan();

        $addMealsToDietPlanService
            ->expects($this->once())
            ->method('setDietPlan')
            ->with($dietPlanCreatedEvent->dietPlan);
        $addMealsToDietPlanService
            ->expects($this->once())
            ->method('setUp');

        $createMealsForDietPlan = new CreateMealsForDietPlan($addMealsToDietPlanService);
        $createMealsForDietPlan->handle($dietPlanCreatedEvent);
    }
}
