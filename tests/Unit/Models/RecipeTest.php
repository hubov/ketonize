<?php

namespace Tests\Unit\Models;

use App\Models\Ingredient;
use App\Models\Recipe;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RecipeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function ingredients_recipe_relation_existence()
    {
        $this->assertTrue(method_exists(Recipe::class, 'ingredients'));
    }

    /** @test */
    public function tags_recipe_relation_existence()
    {
        $this->assertTrue(method_exists(Recipe::class, 'tags'));
    }

    /** @test */
    public function setting_slug_name_total_time_for_recipe_at_creating()
    {
        $recipe = Recipe::create([
           'name' => 'grzegżółka stara',
           'image' => 'default',
           'description' => 'some description',
           'preparation_time' => 10,
           'cooking_time' => 5
        ]);

        $this->assertEquals('grzegzolka-stara', $recipe->slug);
        $this->assertEquals('Grzegżółka stara', $recipe->name);
        $this->assertEquals(15, $recipe->total_time);
    }

    /** @test */
    public function setting_slug_name_total_time_for_recipe_at_updating()
    {
        $recipe = Recipe::create([
            'name' => 'grzegżółka stara',
            'image' => 'default',
            'description' => 'some description',
            'preparation_time' => 10,
            'cooking_time' => 5
        ]);

        $recipe->update([
           'name' => 'grzegżółka mała',
           'preparation_time' => 20,
           'cooking_time' => 10
        ]);

        $this->assertEquals('grzegzolka-mala', $recipe->slug);
        $this->assertEquals('Grzegżółka mała', $recipe->name);
        $this->assertEquals(30, $recipe->total_time);
    }

    /** @test */
    public function setting_slug_name_total_time_for_recipe_at_saving()
    {
        $recipe = Recipe::create([
            'name' => 'grzegżółka stara',
            'image' => 'default',
            'description' => 'some description',
            'preparation_time' => 10,
            'cooking_time' => 5
        ]);

        $recipe->name = 'grzegżółka mała';
        $recipe->preparation_time = 20;
        $recipe->cooking_time = 10;
        $recipe->save();

        $this->assertEquals('grzegzolka-mala', $recipe->slug);
        $this->assertEquals('Grzegżółka mała', $recipe->name);
        $this->assertEquals(30, $recipe->total_time);
    }

    /** @test */
    public function tagsIds_method_in_recipe()
    {
        $recipe = Recipe::factory()->has(Tag::factory()->count(3))->create();

        $result = count($recipe->tagsIds());

        $this->assertEquals(3, $result);
    }

    /** @test */
    public function recipe_resetMacros_method()
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

    /** @test */
    public function recipe_removeIngredients_method()
    {
        $recipe = Recipe::factory()->hasAttached(Ingredient::factory(), ['amount' => 100])->create();

        $recipe->removeIngredients();

        $this->assertEquals(0, $recipe->ingredients->count());
        $this->assertEquals(0, $recipe->kcal);
    }

    /** @test */
    public function recipe_addMacrosFromIngredient_method()
    {
        $recipe = Recipe::factory()->make([
            'protein' => 0,
            'fat' => 0,
            'carbohydrate' => 0,
            'kcal' => 0
        ]);
        $ingredient = Ingredient::factory()->make([
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

    /** @test */
    public function recipe_updateMacroRatios_method()
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
