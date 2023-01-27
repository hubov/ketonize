<?php

namespace Tests\Feature\User;

use App\Models\Ingredient;
use App\Models\Profile;
use App\Models\Recipe;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class RecipeControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->has(Profile::factory())->create();
    }

    /** @test */
    public function recipes_screen_for_user_can_be_rendered()
    {
        $recipe = Recipe::factory()->create();
        $response = $this->actingAs($this->user)->get('/recipes');

        $response->assertStatus(200);
        $response->assertSee($recipe->name);
    }

    /** @test */
    public function recipes_screen_without_user_is_redirected()
    {
        $response = $this->get('/recipes');

        $response->assertRedirect('/login');
    }

    /** @test */
    public function single_recipe_screen_for_user_can_be_rendered()
    {
        $recipe = Recipe::factory()->hasAttached(Ingredient::factory(), ['amount' => 100])->create();
        $response = $this->actingAs($this->user)->get('/recipe/'.$recipe->slug);

        $response->assertStatus(200);
        $response->assertSee($recipe->name);
        $response->assertSee($recipe->description);
        $response->assertSeeInOrder([$recipe->ingredients->first()->name, $recipe->ingredients->first()->pivot->amount]);
    }

    /** @test */
    public function single_recipe_screen_with_false_slug_cannot_be_rendered()
    {
        $recipe = Recipe::factory()->create();
        $response = $this->actingAs($this->user)->get('/recipe/aaaaa'.$recipe->slug);

        $response->assertStatus(404);
    }

    /** @test */
    public function single_recipe_screen_without_user_is_redirected()
    {
        $recipe = Recipe::factory()->create();
        $response = $this->get('/recipe/'.$recipe->slug);

        $response->assertRedirect('/login');
    }

    /** @test */
    public function prevent_adding_new_recipe_by_user()
    {
        $request = $this->actingAs($this->user)->post('/recipes', []);

        $request->assertStatus(403);
    }

    /** @test */
    public function prevent_adding_new_recipe_when_not_signed_in()
    {
        $request = $this->post('/recipes', []);

        $request->assertStatus(403);
    }

    /** @test */
    public function search_as_guest_is_redirected()
    {
        Recipe::factory()->create(['name' => 'aaa']);
        Recipe::factory()->create(['name' => 'bbb']);
        $query = [
            'searchFilter' => [
                'tags' => 0,
                'query' => 'a'
            ]
        ];

        $response = $this->post('/recipes/search', $query);

        $response->assertRedirectContains('/login');
    }

    /** @test */
    public function search_by_query_as_signed_in_user()
    {
        $result = Recipe::factory()->create(['name' => 'aaa']);
        Recipe::factory()->create(['name' => 'bbb']);
        $query = [
            'searchFilter' => [
                'tags' => 0,
                'query' => 'a'
            ]
        ];

        $response = $this->actingAs($this->user)->post('/recipes/search', $query);

        $response->assertStatus(200);
        $response->assertJson($this->searchResultFormat($result));
    }

    /** @test */
    public function search_by_tag_as_signed_in_user()
    {
        $result = Recipe::factory()->has(Tag::factory())->create(['name' => 'aaa']);
        Recipe::factory()->has(Tag::factory())->create(['name' => 'bbb']);
        $query = [
            'searchFilter' => [
                'tags' => $result->tags[0]->id
            ]
        ];

        $response = $this->actingAs($this->user)->post('/recipes/search', $query);

        $response->assertStatus(200);
        $response->assertJson($this->searchResultFormat($result));
    }

    /** @test */
    public function getting_raw_recipe_data_as_guest_is_redirected()
    {
        $recipe = Recipe::factory()->create();

        $response = $this->post('/recipe/search', ['slug' => $recipe->slug]);

        $response->assertRedirectContains('/login');
    }

    /** @test */
    public function getting_raw_recipe_data_as_user_returns_json()
    {
        $recipe = Recipe::factory()->hasAttached(Ingredient::factory(), ['amount' => 100])->create();
        $ingredient = $recipe->ingredients->first();

        $response = $this->actingAs($this->user)->post('/recipe/search', ['slug' => $recipe->slug]);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'name' => $recipe->name,
            'image' => $recipe->image,
            'description' => $recipe->description
        ]);
        $response->assertJsonFragment([
                'id' => $ingredient->id,
                'name' => $ingredient->name,
                'protein' => $ingredient->protein,
                'fat' => $ingredient->fat,
                'carbohydrate' => $ingredient->carbohydrate,
                'kcal' => $ingredient->kcal,
                'ingredient_category_id' => $ingredient->ingredient_category_id,
                'unit_id' => $ingredient->unit_id
        ]);
        $response->assertJsonFragment([
            'amount' => $ingredient->pivot->amount
        ]);
        $response->assertJsonFragment([
            'id' => $ingredient->unit->id,
            'name' => $ingredient->unit->name,
            'symbol' => $ingredient->unit->symbol
        ]);
    }

    protected function searchResultFormat($recipe)
    {
        return [
            [
                'name' => Str::ucfirst($recipe->name),
                'slug' => $recipe->slug,
                'image' => $recipe->image,
                'protein_ratio' => $recipe->protein_ratio,
                'fat_ratio' => $recipe->fat_ratio,
                'carbohydrate_ratio' => $recipe->carbohydrate_ratio,
                'preparation_time' => $recipe->preparation_time,
                'total_time' => $recipe->total_time
            ]
        ];
    }
}
