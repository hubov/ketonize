<?php

namespace Tests\Unit\Listeners;

use App\Events\UserDietChanged;
use App\Listeners\CreateDietPlans;
use App\Models\User;
use App\Services\Interfaces\DietPlanInterface;
use PHPUnit\Framework\TestCase;

class CreateDietPlansTest extends TestCase
{
    /** @test **/
    public function it_updates_all_diet_plans()
    {
        $dietPlanService = $this->createMock(DietPlanInterface::class);
        $userDietChangedEvent = $this->createStub(UserDietChanged::class);
        $userDietChangedEvent->userDiet = new \stdClass();
        $userDietChangedEvent->userDiet->user = new User();

        $dietPlanService
            ->expects($this->once())
            ->method('setUser')
            ->with($userDietChangedEvent->userDiet->user);
        $dietPlanService
            ->expects($this->once())
            ->method('updateAll');

        $createDietPlans = new CreateDietPlans($dietPlanService);
        $createDietPlans->handle($userDietChangedEvent);
    }
}
