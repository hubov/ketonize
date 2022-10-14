<?php

namespace Tests\Unit;

use App\Models\Ingredient;
use App\Models\Recipe;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RecipeTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_ingredients_recipe_relation_existence()
    {
        $this->assertTrue(method_exists(Recipe::class, 'ingredients'));
    }

    public function test_tags_recipe_relation_existence()
    {
        $this->assertTrue(method_exists(Recipe::class, 'tags'));
    }

    public function test_tagsIds_method_in_recipe()
    {
        $recipe = Recipe::factory()->has(Tag::factory()->count(3))->create();

        $result = count($recipe->tagsIds());

        $this->assertEquals(3, $result);
    }

    public function test_recipe_resetMacros_method()
    {
        $recipe = Recipe::factory()->create([
            'protein' => 10,
            'fat' => 10,
            'carbohydrate' => 10,
            'kcal' => 100,
            'protein_ratio' => 33,
            'fat_ratio' => 33,
            'carbohydrate_ratio' => 33
        ]);

        $recipe->resetMacros();

        $this->assertEquals([
            0,
            0,
            0,
            0,
            0,
            0,
            0
        ], [
            $recipe->protein,
            $recipe->fat,
            $recipe->carbohydrate,
            $recipe->kcal,
            $recipe->protein_ratio,
            $recipe->fat_ratio,
            $recipe->carbohydrate_ratio
        ]);
    }

    public function test_recipe_setIngredients_method()
    {
        $recipe = Recipe::factory()->hasAttached(Ingredient::factory(), ['amount' => 100])->create();

        $this->assertEquals(1, count($recipe->ingredients));
    }

    public function test_recipe_removeIngredients_method()
    {
        $recipe = Recipe::factory()->hasAttached(Ingredient::factory(), ['amount' => 100])->create();

        $recipe->removeIngredients();

        $this->assertEquals(0, count($recipe->ingredients));
    }

    public function test_recipe_addMacrosFromIngredient_method()
    {
        $recipe = Recipe::factory()->create([
            'protein' => 0,
            'fat' => 0,
            'carbohydrate' => 0,
            'kcal' => 0
        ]);
        $ingredient = Ingredient::factory()->create([
            'protein' => 10,
            'fat' => 20,
            'carbohydrate' => 30,
            'kcal' => 40
        ]);

        $recipe->addMacrosFromIngredient($ingredient, 100);

        $this->assertEquals([
            10,
            20,
            30,
            40
        ], [
            $recipe->protein,
            $recipe->fat,
            $recipe->carbohydrate,
            $recipe->kcal
        ]);
    }

    public function test_recipe_updateMacroRatios_method()
    {
        $recipe = Recipe::factory()->create([
            'protein' => 10,
            'fat' => 20,
            'carbohydrate' => 30
        ]);

        $recipe->updateMacroRatios();

        $this->assertEquals([
            17,
            33,
            50
        ],
        [
            $recipe->protein_ratio,
            $recipe->fat_ratio,
            $recipe->carbohydrate_ratio
        ]);

    }
}
