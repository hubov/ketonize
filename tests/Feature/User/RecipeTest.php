<?php

namespace Tests\Feature\User;

use App\Models\Ingredient;
use App\Models\Recipe;
use App\Models\Role;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RecipeTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_recipes_screen_for_user_can_be_rendered()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/recipes');

        $response->assertStatus(200);
    }

    public function test_recipes_screen_without_user_is_redirected()
    {
        $response = $this->get('/recipes');

        $response->assertRedirect('/login');
    }

    public function test_single_recipe_screen_for_user_can_be_rendered()
    {
        $user = User::factory()->create();
        $recipe = Recipe::factory()->create();
        $response = $this->actingAs($user)->get('/recipe/'.$recipe->slug);

        $response->assertStatus(200);
    }

    public function test_single_recipe_screen_with_false_slug_cannot_be_rendered()
    {
        $user = User::factory()->create();
        $recipe = Recipe::factory()->create();
        $response = $this->actingAs($user)->get('/recipe/aaaaa'.$recipe->slug);

        $response->assertStatus(404);
    }

    public function test_single_recipe_screen_without_user_is_redirected()
    {
        $recipe = Recipe::factory()->create();
        $response = $this->get('/recipe/'.$recipe->slug);

        $response->assertRedirect('/login');
    }

    public function test_single_recipe_screen_for_admin_can_be_rendered()
    {
        $user = User::factory()->has(Role::factory()->state(['name' => 'admin']))->create();
        $recipe = Recipe::factory()->create();
        $response = $this->actingAs($user)->get('/recipe/'.$recipe->slug);

        $response->assertViewHas('admin', TRUE);
        $response->assertStatus(200);
    }

    public function test_adding_new_recipe_by_admin() {
        $user = User::factory()->has(Role::factory()->state(['name' => 'admin']))->create();
        $ingredient = Ingredient::factory()->create();
        $tag = Tag::factory()->create();
        $amount = 100;

        $requestData = [
            'name' => 'Recipe #1',
            'slug' => 'recipe-1',
            'image' => '',
            'description' => 'Some recipe description.',
            'preparation_time' => 10,
            'cooking_time' => 5,
            'ids' => [$ingredient->id],
            'quantity' => [$amount],
            'tags' => [$tag->id]
        ];

        $request = $this->actingAs($user)->post('/recipes', $requestData);

        $recipeIs = Recipe::where('slug', 'recipe-1')->first();

        unset($requestData['image']);
        unset($requestData['ids']);
        unset($requestData['quantity']);
        unset($requestData['tags']);
        $recipeExpected = Recipe::factory()->create($requestData);
        $recipeExpected->setIngredients([$ingredient->id], [$amount]);
        $recipeExpected->tags()->attach($tag->id);

        $request->assertRedirect('/recipe/recipe-1');
        $request->assertLocation('/recipe/recipe-1');
        $this->assertObjectEquals($recipeExpected, $recipeIs);
    }

    public function test_prevent_adding_new_recipe_by_user() {
        $user = User::factory()->create();

        $request = $this->actingAs($user)->post('/recipes', []);

        $request->assertStatus(403);
    }

    public function test_prevent_adding_new_recipe_when_not_signed_in() {
        $request = $this->post('/recipes', []);

        $request->assertStatus(403);
    }

    public function test_updating_recipe_by_admin() {
        $user = User::factory()->has(Role::factory()->state(['name' => 'admin']))->create();
        $recipe = Recipe::factory()->hasAttached(Ingredient::factory(), ['amount' => '100'])->has(Tag::factory())->create();

        $ingredientNew = Ingredient::factory()->create();
        $tagNew = Tag::factory()->create();
        $amount = 100;

        foreach ($recipe->ingredients as $ingredient) {
            $ingredients[] = $ingredient->id;
            $amounts[] = $ingredient->pivot->amount;
        }
        $ingredients[] = $ingredientNew->id;
        $amounts[] = $amount;

        foreach ($recipe->tags as $tag) {
            $tags[] = $tag->id;
        }
        $tags[] = $tagNew->id;

        $requestData = [
            'name' => 'Recipe #1',
            'slug' => 'recipe-1',
            'image' => '',
            'description' => 'Some recipe description.',
            'preparation_time' => 10,
            'cooking_time' => 5,
            'ids' => $ingredients,
            'quantity' => $amounts,
            'tags' => $tags
        ];

        $request = $this->actingAs($user)->put('/recipe/'.$recipe->slug.'/edit', $requestData);

        $recipeIs = Recipe::where('slug', 'recipe-1')->first();

        unset($requestData['image']);
        unset($requestData['ids']);
        unset($requestData['quantity']);
        unset($requestData['tags']);
        $recipeExpected = Recipe::factory()->create($requestData);
        $recipeExpected->setIngredients($ingredients, $amounts);
        $recipeExpected->tags()->attach($tags);

        $request->assertRedirect('/recipe/recipe-1');
        $request->assertLocation('/recipe/recipe-1');
        $this->assertObjectEquals($recipeExpected, $recipeIs);
    }
}
