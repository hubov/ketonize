<?php

namespace Tests\Unit;

use App\Models\Ingredient;
use App\Models\Meal;
use App\Models\Recipe;
use PHPUnit\Framework\TestCase;

class MealTest extends TestCase
{
    /** @test */
    public function dietPlan_meal_relation_exists()
    {
        $this->assertTrue(method_exists(Meal::class, 'dietPlan'));
    }

    /** @test */
    public function recipe_meal_relation_exists()
    {
        $this->assertTrue(method_exists(Meal::class, 'recipe'));
    }

    /** @test */
    public function it_returns_macros_adjusted_by_modifier()
    {
        $recipe = new Recipe();
        $recipe->protein = 100;
        $recipe->fat = 90;
        $recipe->carbohydrate = 50;
        $recipe->kcal = 300;

        $meal = new Meal();
        $meal->modifier = 50;
        $meal->recipe = $recipe;

        $this->assertEquals(50, $meal->protein);
        $this->assertEquals(45, $meal->fat);
        $this->assertEquals(25, $meal->carbohydrate);
        $this->assertEquals(150, $meal->kcal);
    }

    /** @test */
    public function it_returns_macros_shares()
    {
        $recipe = new Recipe();
        $recipe->protein = 20;
        $recipe->fat = 50;
        $recipe->carbohydrate = 30;

        $meal = new Meal();
        $meal->modifier = 50;
        $meal->recipe = $recipe;

        $this->assertEquals(20, $meal->shareProtein);
        $this->assertEquals(50, $meal->shareFat);
        $this->assertEquals(30, $meal->shareCarbohydrate);
    }

    /** @test */
    public function it_returns_meal_times()
    {
        $recipe = new Recipe();
        $recipe->preparation_time = 20;
        $recipe->cooking_time = 50;
        $recipe->total_time = 70;

        $meal = new Meal();
        $meal->modifier = 50;
        $meal->recipe = $recipe;

        $this->assertEquals(20, $meal->preparation_time);
        $this->assertEquals(50, $meal->cooking_time);
        $this->assertEquals(70, $meal->total_time);
    }

    /** @test */
    public function it_returns_ingredients_with_amount_adjusted_by_modifier()
    {
        $recipe = new Recipe();
        $ingredient = new Ingredient;
        $ingredient->pivot = new \stdClass();
        $ingredient->pivot->amount = 100;
        $recipe->ingredients = collect([$ingredient]);

        $meal = new Meal();
        $meal->modifier = 50;
        $meal->recipe = $recipe;
        $ingredient->amount = 50;

        $this->assertEquals(
            collect([
                $ingredient
            ]),
            $meal->ingredients
        );
    }
}
