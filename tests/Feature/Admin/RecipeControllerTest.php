<?php

namespace Tests\Feature\Admin;

use App\Models\Ingredient;
use App\Models\Profile;
use App\Models\Recipe;
use App\Models\Role;
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

        $this->user = User::factory()->has(Profile::factory())->has(Role::factory()->state(['name' => 'admin']))->create();
    }

    /** @test */
    public function single_recipe_screen_for_admin_can_be_rendered()
    {
        $recipe = Recipe::factory()->create();
        $response = $this->actingAs($this->user)->get('/recipe/'.$recipe->slug);

        $response->assertViewHas('admin', TRUE);
        $response->assertStatus(200);
    }

    /** @test */
    public function adding_new_recipe_by_admin()
    {
        $ingredient = Ingredient::factory()->create();
        $tag = Tag::factory()->create();
        $amount = 100;

        $requestData = [
            'recipe' => [
                'name' => 'Recipe #1',
                'slug' => 'recipe-1',
                'image' => '',
                'description' => 'Some recipe description.',
                'preparation_time' => 10,
                'cooking_time' => 5
            ],
            'ingredients' => [
                [
                    'id' => $ingredient->id,
                    'quantity' => $amount
                ]
            ],
            'tags' => [$tag->id]
        ];

        $request = $this->actingAs($this->user)->post('/recipes', $requestData);

        $request->assertRedirect('/recipe/recipe-1');
        $request->assertLocation('/recipe/recipe-1');
        $this->followRedirects($request)
            ->assertSee($requestData['recipe']['name'])
            ->assertSee($requestData['recipe']['description']);
    }

    /** @test */
    public function updating_recipe_by_admin()
    {
        $recipe = Recipe::factory()->hasAttached(Ingredient::factory(), ['amount' => '100'])->has(Tag::factory())->create();

        $ingredientNew = Ingredient::factory()->create();
        $tagNew = Tag::factory()->create();
        $amount = 100;

        foreach ($recipe->ingredients as $key => $ingredient) {
            $ingredients[$key]['id'] = $ingredient->id;
            $ingredients[$key]['quantity'] = $ingredient->pivot->amount;
        }
        $ingredients[count($ingredients)]['id'] = $ingredientNew->id;
        $ingredients[count($ingredients)-1]['quantity'] = $amount;

        foreach ($recipe->tags as $tag) {
            $tags[] = $tag->id;
        }
        $tags[] = $tagNew->id;

        $requestData = [
            'recipe' => [
                'name' => 'Recipe #1',
                'slug' => 'recipe-1',
                'image' => '',
                'description' => 'Some recipe description.',
                'preparation_time' => 10,
                'cooking_time' => 5
            ],
            'ingredients' => $ingredients,
            'tags' => $tags
        ];

        $request = $this->actingAs($this->user)->put('/recipe/'.$recipe->slug.'/edit', $requestData);

        $request->assertRedirect('/recipe/recipe-1');
        $request->assertLocation('/recipe/recipe-1');
        $this->followRedirects($request)
            ->assertSee($requestData['recipe']['name'])
            ->assertSee($requestData['recipe']['description']);
    }
}
