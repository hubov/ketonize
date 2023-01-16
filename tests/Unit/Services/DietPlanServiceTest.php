<?php

namespace Tests\Unit\Services;

use App\Models\DietPlan;
use App\Models\Meal;
use App\Models\Recipe;
use App\Models\User;
use App\Repositories\Interfaces\DietPlanRepositoryInterface;
use App\Services\DietPlanService;
use App\Services\Interfaces\MealInterface;
use Tests\TestCase;

class DietPlanServiceTest extends TestCase
{
    protected $user;
    protected $dietPlanRepository;
    protected $mealService;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = new User();
        $this->user->setAttribute('id', 1);

        $this->mealService = $this->createMock(MealInterface::class);
        $this->dietPlanRepository = $this->createMock(DietPlanRepositoryInterface::class);
    }

    /** @test  */
    public function diet_plan_can_be_retrieved_by_date()
    {
        $date = "2022-01-01";
        $expectedResult = new DietPlan();

        $this->dietPlanRepository
            ->expects($this->once())
            ->method('getByDate')
            ->with($this->user->id, $date)
            ->willReturn($expectedResult);

        $dietPlanService = new DietPlanService($this->dietPlanRepository, $this->mealService);

        $result = $dietPlanService
            ->setUser($this->user)
            ->getByDate($date);

        $this->assertSame($expectedResult, $result);
    }

    /** @test */
    public function getDates_returns_array_of_current_previous_and_next_date()
    {
        $date = "2022-01-01";
        $expectedResult = [
            'current' => '2022-01-01',
            'next' => '2022-01-02',
            'prev' => '2021-12-31',
        ];

        $dietPlanService = new DietPlanService($this->dietPlanRepository, $this->mealService);
        $dietPlanService
            ->setUser($this->user)
            ->getByDate($date);

        $result = $dietPlanService
            ->getDates();

        $this->assertSame($expectedResult, $result);
    }

    /** @test */
    public function changing_meal_returns_new_recipe()
    {
        $date = "2022-01-01";
        $meal = 1;
        $newSlug = 'new-recipe-slug';

        $dietPlanStub = new DietPlan();
        $recipeStub = new Recipe();
        $mealStub = new Meal();
        $mealStub->setAttribute('recipe', $recipeStub);

        $this->dietPlanRepository
            ->expects($this->once())
            ->method('getByDate')
            ->with($this->user->id, $date)
            ->willReturn($dietPlanStub);

        $this->mealService
            ->expects($this->once())
            ->method('setDietPlan')
            ->willReturn($this->mealService);
        $this->mealService
            ->expects($this->once())
            ->method('change')
            ->with($meal, $newSlug)
            ->willReturn($mealStub);

        $dietPlanService = new DietPlanService($this->dietPlanRepository, $this->mealService);
        $dietPlanService
            ->setUser($this->user)
            ->getByDate($date);

        $result = $dietPlanService->changeMeal($meal, $newSlug);

        $this->assertIsObject($result);
        $this->assertSame($result, $recipeStub);
    }

    /** @test */
    public function missing_diet_plan_on_last_day_of_subscription_creates_new_one()
    {
        $this->withoutEvents();
        $dietPlanStub = new DietPlan();

        $dietPlanService = new DietPlanService($this->dietPlanRepository, $this->mealService);

        $this->dietPlanRepository
            ->expects($this->once())
            ->method('getByDate')
            ->with($this->user->id, $dietPlanService->getLastSubscriptionDay())
            ->willReturn(null);
        $this->dietPlanRepository
            ->expects($this->once())
            ->method('createForUserOnDate')
            ->with($this->user->id, $dietPlanService->getLastSubscriptionDay())
            ->willReturn($dietPlanStub);

        $dietPlanService = new DietPlanService($this->dietPlanRepository, $this->mealService);

        $newDietPlan = $dietPlanService
            ->setUser($this->user)
            ->createIfNotExists();

        $this->assertEquals($newDietPlan, $dietPlanStub);
    }

    /** @test */
    public function existing_diet_plan_on_last_day_of_subscription_ignores_creation_of_a_new_one()
    {
        $this->withoutEvents();
        $dietPlanStub = new DietPlan();

        $dietPlanService = new DietPlanService($this->dietPlanRepository, $this->mealService);

        $this->dietPlanRepository
            ->expects($this->once())
            ->method('getByDate')
            ->with($this->user->id, $dietPlanService->getLastSubscriptionDay())
            ->willReturn($dietPlanStub);
        $this->dietPlanRepository
            ->expects($this->never())
            ->method('createForUserOnDate');

        $dietPlanService = new DietPlanService($this->dietPlanRepository, $this->mealService);

        $newDietPlan = $dietPlanService
            ->setUser($this->user)
            ->createIfNotExists();

        $this->assertNull($newDietPlan);
    }

    /** @test */
    public function updateAll_method_changes_all_diet_plans_starting_from_today()
    {
        $this->withoutEvents();
        $newDietPlanCollection = collect([new DietPlan(), new DietPlan()]);

        $dietPlanService = new DietPlanService($this->dietPlanRepository, $this->mealService);

        $this->dietPlanRepository
            ->expects($this->once())
            ->method('deleteForUserAfterDate')
            ->with($this->user->id, $dietPlanService->getToday())
            ->willReturn(true);
        $this->dietPlanRepository
            ->expects($this->once())
            ->method('createForUserBulk')
            ->with($this->user->id, $dietPlanService->getSubscriptionDatesArray())
            ->willReturn($newDietPlanCollection);

        $dietPlanService = new DietPlanService($this->dietPlanRepository, $this->mealService);

        $newDietPlans = $dietPlanService
            ->setUser($this->user)
            ->updateAll();

        $this->assertSame($newDietPlanCollection, $newDietPlans);
    }

    /** @test */
    public function updateOnDate_method_creates_new_diet_plan()
    {
        $this->withoutEvents();
        $date = '2022-01-01';
        $newDietPlanStub = new DietPlan();

        $this->dietPlanRepository
            ->expects($this->once())
            ->method('deleteForUserOnDate')
            ->with($this->user->id, $date)
            ->willReturn(true);
        $this->dietPlanRepository
            ->expects($this->once())
            ->method('createForUserOnDate')
            ->with($this->user->id, $date)
            ->willReturn($newDietPlanStub);

        $dietPlanService = new DietPlanService($this->dietPlanRepository, $this->mealService);

        $newDietPlan = $dietPlanService
            ->setUser($this->user)
            ->updateOnDate($date);

        $this->assertSame($newDietPlanStub, $newDietPlan);
    }

    /**
     * @test
     * @dataProvider deletionStatuses
     */
    public function delete_all_diet_plans_returns_deletion_status(bool $expectedStatus)
    {
        $this->dietPlanRepository
            ->expects($this->once())
            ->method('deleteForUser')
            ->with($this->user->id)
            ->willReturn($expectedStatus);

        $dietPlanService = new DietPlanService($this->dietPlanRepository, $this->mealService);

        $actualStatus = $dietPlanService
            ->setUser($this->user)
            ->delete();

        $this->assertSame($expectedStatus, $actualStatus);
    }

    /**
     * @test
     * @dataProvider deletionStatuses
     */
    public function delete_diet_plans_after_given_date_returns_deletion_status(bool $expectedStatus)
    {
        $date = '2022-01-01';

        $this->dietPlanRepository
            ->expects($this->once())
            ->method('deleteForUserAfterDate')
            ->with($this->user->id, $date)
            ->willReturn($expectedStatus);

        $dietPlanService = new DietPlanService($this->dietPlanRepository, $this->mealService);

        $actualStatus = $dietPlanService
            ->setUser($this->user)
            ->deleteAfterDate($date);

        $this->assertSame($expectedStatus, $actualStatus);
    }

    /**
     * @test
     * @dataProvider deletionStatuses
     */
    public function delete_diet_plans_on_given_date_returns_deletion_status(bool $expectedStatus)
    {
        $date = '2022-01-01';

        $this->dietPlanRepository
            ->expects($this->once())
            ->method('deleteForUserOnDate')
            ->with($this->user->id, $date)
            ->willReturn($expectedStatus);

        $dietPlanService = new DietPlanService($this->dietPlanRepository, $this->mealService);

        $actualStatus = $dietPlanService
            ->setUser($this->user)
            ->deleteOnDate($date);

        $this->assertSame($expectedStatus, $actualStatus);
    }

    public function deletionStatuses()
    {
        return [
            [true],
            [false]
        ];
    }

    /**
     * @test
     * @dataProvider updateStatuses
     */
    public function checking_update_status_returns_it(bool $expectedStatus)
    {
        $dateTime = '2022-01-01 03:00:00';

        $this->dietPlanRepository
            ->expects($this->once())
            ->method('isCompleteForUserAfter')
            ->with($this->user->id, $dateTime)
            ->willReturn($expectedStatus);

        $dietPlanService = new DietPlanService($this->dietPlanRepository, $this->mealService);

        $actualStatus = $dietPlanService
            ->setUser($this->user)
            ->isUpdatedAfter($dateTime);

        $this->assertSame($expectedStatus, $actualStatus);
    }

    public function updateStatuses()
    {
        return [
            [true],
            [false]
        ];
    }
}
