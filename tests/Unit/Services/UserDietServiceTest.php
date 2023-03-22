<?php

namespace Tests\Unit\Services;

use App\Events\UserDietChanged;
use App\Models\Diet;
use App\Models\DietMealDivision;
use App\Models\Profile;
use App\Models\User;
use App\Models\UserDiet;
use App\Repositories\Interfaces\DietMealDivisionRepositoryInterface;
use App\Repositories\Interfaces\DietRepositoryInterface;
use App\Repositories\Interfaces\UserDietRepositoryInterface;
use App\Services\UserDietService;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class UserDietServiceTest extends TestCase
{
    public $userDietRepository;
    public $dietRepository;
    public $dietMealDivRepository;
    public $userDietService;
    public $mealsCount;

    public function setUp(): void
    {
        parent::setUp();

        $this->userId = 1;
        $this->user = new User();
        $this->user->id = $this->userId;
        $this->profile = new Profile();
        $this->profile->user_id = $this->userId;
        $this->profile->birthday = '2000-01-01';
        $this->dietId = 2;
        $this->mealsCount = 3;
        $this->dietMealDiv = new DietMealDivision();
        $this->dietMealDiv->id = 1;
        $this->diet = new Diet();
        $this->diet->id = $this->dietId;
        $this->attributes = [
            'diet_id' => $this->dietId,
            'diet_meal_division_id' => $this->dietMealDiv->id,
            'kcal' => 0,
            'protein' => 0,
            'fat' => 0,
            'carbohydrate' => 0
        ];

        $this->userDietRepository = $this->createMock(UserDietRepositoryInterface::class);
        $this->dietRepository = $this->createMock(DietRepositoryInterface::class);
        $this->dietMealDivRepository = $this->createMock(DietMealDivisionRepositoryInterface::class);

        $this->dietMealDivRepository
            ->expects($this->atLeastOnce())
            ->method('getByMealsCount')
            ->with($this->mealsCount)
            ->willReturn($this->dietMealDiv);

        $this->dietRepository
            ->expects($this->atLeastOnce())
            ->method('get')
            ->with($this->dietId)
            ->willReturn($this->diet);

        $this->userDietService = new UserDietService(
            $this->userDietRepository,
            $this->dietRepository,
            $this->dietMealDivRepository
        );
    }

    /** @test */
    public function it_sets_diet_meal_division_given_meals_count()
    {
        $this->userDietService
            ->setDiet($this->dietId)
            ->setMealsDivision($this->mealsCount)
            ->setProfile($this->profile);

        $this->assertNotEquals(
            $this->userDietService,
            new UserDietService(
                $this->userDietRepository,
                $this->dietRepository,
                $this->dietMealDivRepository
            )
        );
    }

    /** @test */
    public function new_diet_plan_is_created()
    {
        $this->userDietRepository
            ->expects($this->once())
            ->method('createForUser')
            ->with($this->profile->user_id, $this->attributes)
            ->willReturn(new UserDiet());

        Event::fake();

        $this->assertEquals(
            new UserDiet(),
            $this->userDietService
                ->setDiet($this->dietId)
                ->setMealsDivision($this->mealsCount)
                ->setProfile($this->profile)
                ->create()
        );
        Event::assertDispatched(UserDietChanged::class);
    }

    /** @test */
    public function diet_plan_can_be_updated()
    {
        $this->userDietRepository
            ->expects($this->once())
            ->method('updateForUser')
            ->with($this->profile->user_id, $this->attributes)
            ->willReturn(new UserDiet());

        Event::fake();

        $this->assertEquals(
            new UserDiet(),
            $this->userDietService
                ->setDiet($this->dietId)
                ->setMealsDivision($this->mealsCount)
                ->setProfile($this->profile)
                ->update()
        );
        Event::assertDispatched(UserDietChanged::class);
    }
}
