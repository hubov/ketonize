<?php

namespace Tests\Unit\Services;

use App\Models\DietPlan;
use App\Models\Ingredient;
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
    public function it_changes_a_meal()
    {
        $oldMeal = new Meal();
        $oldMeal->id = 1;
        $oldMeal->recipe = new Recipe();
        $oldMeal->recipe->kcal = 300;
        $oldMeal->modifier = 100;
        $recipe = new Recipe();
        $recipe->id = 1;
        $recipe->kcal = 600;
        $recipe->slug = 'new-recipe';
        $mealOrder = 1;

        $this->mealRepository
            ->expects($this->once())
            ->method('getByMeal')
            ->with($this->dietPlan->id, $mealOrder)
            ->willReturn(collect([$oldMeal]));
        $this->mealRepository
            ->expects($this->once())
            ->method('delete')
            ->with($oldMeal->id)
            ->willReturn(true);

        $this->recipeRepository
            ->expects($this->once())
            ->method('getBySlug')
            ->with($recipe->slug)
            ->willReturn($recipe);

        $this->mealRepository
            ->expects($this->once())
            ->method('create')
            ->with([
                'diet_plan_id' => $this->dietPlan->id,
                'modifier' => 50,
                'recipe_id' => $recipe->id,
                'meal' => $mealOrder
            ])
            ->willReturn(new Meal());

        $mealService = new MealService($this->recipeRepository, $this->mealRepository);

        $this->assertEquals(
            new Meal(),
            $mealService
                ->setDietPlan($this->dietPlan)
                ->change($mealOrder, $recipe->slug)
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

    /** @test */
    public function it_returns_ingredients_list_between_dates()
    {
        $userId = 1;
        $dates = [
            'from' => '2023-01-01',
            'to' => '2023-01-01'
        ];
        $ingredient = new Ingredient();
        $ingredient->id = 1;
        $recipe = new Recipe();
        $recipe->ingredients = collect([$ingredient]);
        $ingredient->pivot = new \stdClass();
        $ingredient->pivot->amount = 100;
        $ingredient->recipe = $recipe;
        $meal = new Meal();
        $meal->modifier = 100;
        $meal->recipe = $recipe;

        $this->mealRepository
            ->expects($this->once())
            ->method('getForUserBetweenDates')
            ->with($userId, $dates['from'], $dates['to'])
            ->willReturn(collect([$meal]));

        $mealService = new MealService($this->recipeRepository, $this->mealRepository);

        $this->assertEquals(
            [
                1 => [
                    'ingredient_id' => 1,
                    'amount' => 100
                 ]
            ],
            $mealService->getIngredientsBetweenDates($userId, $dates['from'], $dates['to'])
        );
    }

    /** @test */
    public function it_returns_empty_array_between_dates_if_no_meals_planned()
    {
        $userId = 1;
        $dates = [
            'from' => '2023-01-01',
            'to' => '2023-01-01'
        ];

        $this->mealRepository
            ->expects($this->once())
            ->method('getForUserBetweenDates')
            ->with($userId, $dates['from'], $dates['to'])
            ->willReturn(collect([]));

        $mealService = new MealService($this->recipeRepository, $this->mealRepository);

        $this->assertEquals(
            [],
            $mealService->getIngredientsBetweenDates($userId, $dates['from'], $dates['to'])
        );
    }
}
