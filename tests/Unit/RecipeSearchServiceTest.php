<?php

namespace Tests\Unit;

use App\Models\Ingredient;
use App\Models\Recipe;
use App\Models\Tag;
use App\Services\Recipe\RecipeSearchService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;


class RecipeSearchServiceTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_search_by_query_recipe_name()
    {
        Recipe::factory()->create(['name' => 'aaa']);
        Recipe::factory()->create(['name' => 'bbb']);
        $recipeSearch = new RecipeSearchService();
        $recipeSearch->filters(['query' => 'a']);
        $result = $recipeSearch->search();

        $this->assertCount(1, $result);
    }

    public function test_search_by_query_ingredient_name()
    {
        Recipe::factory()->hasAttached(Ingredient::factory()->state(['name' => 'aaa']), ['amount' => 1])->create(['name' => 'bbb']);
        Recipe::factory()->hasAttached(Ingredient::factory()->state(['name' => 'ddd']), ['amount' => 1])->create(['name' => 'ccc']);
        $recipeSearch = new RecipeSearchService();
        $recipeSearch->filters(['query' => 'a']);
        $result = $recipeSearch->search();

        $this->assertCount(1, $result);
    }

    public function test_search_by_tag()
    {
        $recipe = Recipe::factory()->has(Tag::factory())->create(['name' => 'aaa']);
        Recipe::factory()->has(Tag::factory())->create(['name' => 'bbb']);
        $recipeSearch = new RecipeSearchService();
        $recipeSearch->filters(['tags' => [$recipe->tags[0]->id]]);

        $result = $recipeSearch->search();

        $this->assertCount(1, $result);
    }
}
