<?php

namespace Tests\Unit\Jobs;

use App\Jobs\GenerateDietPlan;
use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Services\Interfaces\DietPlanInterface;
use PHPUnit\Framework\TestCase;

class GenerateDietPlanTest extends TestCase
{
    /** @test */
    public function it_creates_diet_plans_for_active_users()
    {
        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $dietPlanService = $this->createMock(DietPlanInterface::class);

        $userRepository
            ->expects($this->once())
            ->method('getAllActive')
            ->willReturn(collect([new User(), new User()]));
        $dietPlanService
            ->expects($this->exactly(2))
            ->method('setUser')
            ->with(new User())
            ->willReturnSelf();
        $dietPlanService
            ->expects($this->exactly(2))
            ->method('createIfNotExists');

        $generateDietPlanJob = new GenerateDietPlan();
        $generateDietPlanJob->handle($userRepository, $dietPlanService);
    }
}
