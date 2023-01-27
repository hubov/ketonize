<?php

namespace Tests\Feature\User;

use App\Models\Ingredient;
use App\Models\IngredientCategory;
use App\Models\Recipe;
use App\Models\Role;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IngredientControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $ingredientCategory = IngredientCategory::factory()->create();
        $unit = Unit::factory()->create();
        $this->requestData = [
            'name' => 'Ingredient 1',
            'ingredient_category_id' => $ingredientCategory->id,
            'protein' => 1,
            'fat' => 5,
            'carbohydrate' => 0.5,
            'kcal' => 60,
            'unit_id' => $unit->id
        ];
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->requestData);
    }

    /** @test */
    public function ingredient_listing_screen_without_user_is_redirected()
    {
        $response = $this->get('/ingredients');

        $response->assertRedirect('/login');
    }

    /** @test */
    public function ingredient_listing_screen_can_be_rendered_with_user()
    {
        $user = User::factory()->create();
        $ingredients = Ingredient::factory()->count(2)->create();

        $response = $this->actingAs($user)->get('/ingredients');

        $response->assertStatus(200);
        $response->assertSeeInOrder($ingredients->sortBy('name')->pluck('name')->toArray());
    }

    /** @test */
    public function adding_new_ingredient_as_user_is_forbidden()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/ingredients');

        $response->assertStatus(403);
    }

    /** @test */
    public function adding_new_ingredient_via_ajax_as_user_is_forbidden()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/ingredient/new');

        $response->assertStatus(403);
    }

    /** @test */
    public function ingredient_edit_page_for_user_is_forbidden()
    {
        $user = User::factory()->create();
        $ingredient = Ingredient::factory()->create();

        $response = $this->actingAs($user)->get('/ingredient/'.$ingredient->id);

        $response->assertStatus(403);
    }

    /** @test */
    public function ingredient_edit_page_for_no_user_is_redirected()
    {
        $ingredient = Ingredient::factory()->create();

        $response = $this->get('/ingredient/'.$ingredient->id);

        $response->assertStatus(403);
    }

    /** @test */
    public function updating_ingredient_as_user_is_forbidden()
    {
        $user = User::factory()->create();
        $ingredient = Ingredient::factory()->create();

        $response = $this->actingAs($user)->put('/ingredient/'.$ingredient->id);

        $response->assertStatus(403);
    }

    /** @test */
    public function deleting_ingredient_not_assigned_to_recipe_as_user_is_forbidden()
    {
        $user = User::factory()->create();
        $ingredient = Ingredient::factory()->create();

        $response = $this->actingAs($user)->delete('/ingredient/'.$ingredient->id.'/delete');

        $response->assertStatus(403);
    }

    /** @test */
    public function searching_for_ingredient_returns_results_for_user()
    {
        $user = User::factory()->create();
        $ingredient = Ingredient::factory()->create(['name' => 'Delicious ingredient']);

        $response = $this->actingAs($user)->get('/ingredient-autocomplete?name=ingr');

        $response->assertStatus(200);
        $response->assertJsonFragment(['id' => $ingredient->id, 'name' => 'Delicious ingredient']);
    }

    /** @test */
    public function searching_for_ingredient_for_non_user_is_forbidden()
    {
        Ingredient::factory()->create(['name' => 'Delicious ingredient']);

        $response = $this->get('/ingredient-autocomplete', ['name' => 'ingr']);

        $response->assertRedirect('/login');
    }
}
