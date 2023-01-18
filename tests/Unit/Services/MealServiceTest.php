<?php

namespace Tests\Unit\Services;

use App\Models\DietPlan;
use App\Models\Meal;
use App\Models\Recipe;
use App\Repositories\Interfaces\MealRepositoryInterface;
use App\Repositories\Interfaces\RecipeRepositoryInterface;
use App\Services\MealService;
use PHPUnit\Framework\TestCase;

class MealServiceTest extends TestCase
{
    public $dietPlan;
    public $recipeRepository;
    public $mealRepository;

    public function setUp(): void
    {
        parent::setUp();

        $this->dietPlan = new DietPlan();
        $this->dietPlan->id = 1;

        $this->recipeRepository = $this->createMock(RecipeRepositoryInterface::class);
        $this->mealRepository = $this->createMock(MealRepositoryInterface::class);
    }

    /** @test */
    public function kcal_modifier_is_calculated_correctly()
    {
        $expectedValue = 50;
        $mealKcal = 250;
        $recipeKcal = 500;
        $mealService = new MealService($this->recipeRepository, $this->mealRepository);

        $this->assertEquals($expectedValue, $mealService->calculateModifier($mealKcal, $recipeKcal));
    }

    /** @test */
    public function it_adds_new_meals()
    {
        $recipe = new Recipe();
        $recipe->id = 1;
        $recipe->kcal = 500;

        $kcal = 250;
        $mealOrder = 1;

        $modifier = 50;

        $providedValues = [
            'diet_plan_id' => $this->dietPlan->id,
            'modifier' => $modifier,
            'recipe_id' => $recipe->id,
            'meal' => $mealOrder
        ];

        $expectedResult = new Meal();
        $expectedResult->setRelation('dietPlan', $this->dietPlan);
        $expectedResult->setRelation('recipe', $recipe);
        $expectedResult->modifier = $modifier;
        $expectedResult->mealOrder = $mealOrder;

        $this->mealRepository
            ->expects($this->once())
            ->method('create')
            ->with($providedValues)
            ->willReturn($expectedResult);

        $mealService = new MealService($this->recipeRepository, $this->mealRepository);

        $this->assertEquals(
            $expectedResult,
            $mealService
                ->setDietPlan($this->dietPlan)
                ->add($recipe, $kcal, $mealOrder)
        );
    }

    /** @test */
    public function it_deletes_a_meal()
    {
        $mealId = 1;

        $this->mealRepository
            ->expects($this->once())
            ->method('delete')
            ->with($mealId)
            ->willReturn(true);

        $mealService = new MealService($this->recipeRepository, $this->mealRepository);

        $this->assertTrue(
            $mealService
                ->setDietPlan($this->dietPlan)
                ->delete($mealId)
        );
    }
}
