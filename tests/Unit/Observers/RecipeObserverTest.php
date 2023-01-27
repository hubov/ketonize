<?php

namespace Tests\Unit\Observers;

use App\Models\Ingredient;
use App\Models\Recipe;
use App\Observers\RecipeObserver;
use PHPUnit\Framework\TestCase;

class RecipeObserverTest extends TestCase
{
    public $recipe;

    public function setUp(): void
    {
        $this->recipe = new Recipe();
        $this->recipe->name = 'a new recipe';
        $this->recipe->image = null;
        $this->recipe->preparation_time = 10;
        $this->recipe->cooking_time = 10;
    }

    /** @test */
    public function it_formats_or_fills_missing_attributes()
    {
        $recipeObserver = new RecipeObserver();

        $recipeObserver->saving($this->recipe);

        $this->assertEquals('A new recipe', $this->recipe->name);
        $this->assertEquals('default', $this->recipe->image);
        $this->assertEquals('a-new-recipe', $this->recipe->slug);
        $this->assertEquals(20, $this->recipe->total_time);
    }

    /** @test */
    public function it_updates_macros_after_ingredients_syncing()
    {
        $recipe = $this->createMock(Recipe::class);
        $ingredient = new Ingredient();
        $ingredient->pivot = new \stdClass();
        $ingredient->pivot->amount = 100;

        $recipe
            ->expects($this->atLeastOnce())
            ->method('__get')
            ->with('ingredients')
            ->willReturn(collect([$ingredient]));
        $recipe
            ->expects($this->once())
            ->method('resetMacros');
        $recipe
            ->expects($this->atLeastOnce())
            ->method('addMacrosFromIngredient')
            ->withAnyParameters();
        $recipe
            ->expects($this->once())
            ->method('updateMacroRatios');
        $recipe
            ->expects($this->once())
            ->method('save');

        $recipeObserver = new RecipeObserver();

        $recipeObserver->pivotSynced($recipe, 'ingredients');
    }

    /** @test */
    public function it_does_not_update_macros_after_non_ingredients_relation_syncing()
    {
        $recipe = $this->createMock(Recipe::class);
        $ingredient = new Ingredient();
        $ingredient->pivot = new \stdClass();
        $ingredient->pivot->amount = 100;

        $recipe
            ->expects($this->never())
            ->method('__get')
            ->with('ingredients');
        $recipe
            ->expects($this->never())
            ->method('save');

        $recipeObserver = new RecipeObserver();

        $recipeObserver->pivotSynced($recipe, 'tags');
    }
}
