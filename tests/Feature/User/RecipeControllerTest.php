<?php

namespace Tests\Feature\User;

use App\Models\Ingredient;
use App\Models\Profile;
use App\Models\Recipe;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RecipeControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->has(Profile::factory())->create();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_recipes_screen_for_user_can_be_rendered()
    {
        $response = $this->actingAs($this->user)->get('/recipes');

        $response->assertStatus(200);
    }

    public function test_recipes_screen_without_user_is_redirected()
    {
        $response = $this->get('/recipes');

        $response->assertRedirect('/login');
    }

    public function test_single_recipe_screen_for_user_can_be_rendered()
    {
        $recipe = Recipe::factory()->create();
        $response = $this->actingAs($this->user)->get('/recipe/'.$recipe->slug);

        $response->assertStatus(200);
    }

    public function test_single_recipe_screen_with_false_slug_cannot_be_rendered()
    {
        $recipe = Recipe::factory()->create();
        $response = $this->actingAs($this->user)->get('/recipe/aaaaa'.$recipe->slug);

        $response->assertStatus(404);
    }

    public function test_single_recipe_screen_without_user_is_redirected()
    {
        $recipe = Recipe::factory()->create();
        $response = $this->get('/recipe/'.$recipe->slug);

        $response->assertRedirect('/login');
    }

    public function test_prevent_adding_new_recipe_by_user()
    {
        $request = $this->actingAs($this->user)->post('/recipes', []);

        $request->assertStatus(403);
    }

    public function test_prevent_adding_new_recipe_when_not_signed_in()
    {
        $request = $this->post('/recipes', []);

        $request->assertStatus(403);
    }

    public function test_search_as_guest()
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

    public function test_search_by_query_as_signed_in_user()
    {
        Recipe::factory()->create(['name' => 'aaa']);
        Recipe::factory()->create(['name' => 'bbb']);
        $query = [
            'searchFilter' => [
                'tags' => 0,
                'query' => 'a'
            ]
        ];

        $response = $this->actingAs($this->user)->post('/recipes/search', $query);

        $response->assertStatus(200);
        $response->assertJsonFragment(['aaa']);
    }

    public function test_search_by_tag_as_signed_in_user()
    {
        $recipe = Recipe::factory()->has(Tag::factory())->create(['name' => 'aaa']);
        Recipe::factory()->has(Tag::factory())->create(['name' => 'bbb']);
        $query = [
            'searchFilter' => [
                'tags' => $recipe->tags[0]->id
            ]
        ];

        $response = $this->actingAs($this->user)->post('/recipes/search', $query);

        $response->assertStatus(200);
        $response->assertJsonFragment(['aaa']);
    }

    public function test_returning_raw_recipe_data_as_guest()
    {
        $recipe = Recipe::factory()->create();

        $response = $this->post('/recipe/search', ['slug' => $recipe->slug]);

        $response->assertRedirectContains('/login');
    }

    public function test_returning_raw_recipe_data_as_user()
    {
        $recipe = Recipe::factory()->create();

        $response = $this->actingAs($this->user)->post('/recipe/search', ['slug' => $recipe->slug]);

        $response->assertStatus(200);
        $response->assertJsonFragment([$recipe->name]);
    }
}
