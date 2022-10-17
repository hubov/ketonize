<?php

namespace Tests\Feature\Admin;

use App\Models\Ingredient;
use App\Models\IngredientCategory;
use App\Models\Recipe;
use App\Models\Role;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IngredientTest extends TestCase
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

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_ingredient_listing_screen_without_user_is_redirected()
    {
        $response = $this->get('/ingredients');

        $response->assertRedirect('/login');
    }

    public function test_ingredient_listing_screen_can_be_rendered_with_user()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/ingredients');

        $response->assertStatus(200);
    }

    public function test_adding_new_ingredient_as_admin_with_correct_data()
    {
        $user = User::factory()->has(Role::factory()->state(['name' => 'admin']))->create();

        $response = $this->actingAs($user)->post('/ingredients', $this->requestData);


        $response->assertRedirectContains('/ingredient/');
    }

    public function test_adding_new_ingredient_as_admin_with_incorrect_data()
    {
        $user = User::factory()->has(Role::factory()->state(['name' => 'admin']))->create();
        $this->requestData['protein'] = NULL;

        $response = $this->actingAs($user)->post('/ingredients', $this->requestData);

        $response->assertSessionHasErrors('protein');
    }

    public function test_adding_new_ingredient_as_user_is_forbidden()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/ingredients');

        $response->assertStatus(403);
    }

    public function test_adding_new_ingredient_via_ajax_as_admin_with_correct_data()
    {
        $user = User::factory()->has(Role::factory()->state(['name' => 'admin']))->create();

        $response = $this->actingAs($user)->post('/ingredient/new', $this->requestData);

        $response->assertSee('id');
    }

    public function test_adding_new_ingredient_via_ajax_as_admin_with_incorrect_data()
    {
        $user = User::factory()->has(Role::factory()->state(['name' => 'admin']))->create();
        $this->requestData['protein'] = NULL;

        $response = $this->actingAs($user)->post('/ingredient/new', $this->requestData);

        $response->assertSessionHasErrors('protein');
    }

    public function test_adding_new_ingredient_via_ajax_as_user_is_forbidden()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/ingredient/new');

        $response->assertStatus(403);
    }

    public function test_ingredient_edit_page_is_rendering_for_admin()
    {
        $user = User::factory()->has(Role::factory()->state(['name' => 'admin']))->create();
        $ingredient = Ingredient::factory()->create();

        $response = $this->actingAs($user)->get('/ingredient/'.$ingredient->id);

        $response->assertStatus(200);
    }

    public function test_ingredient_edit_page_for_user_is_forbidden()
    {
        $user = User::factory()->create();
        $ingredient = Ingredient::factory()->create();

        $response = $this->actingAs($user)->get('/ingredient/'.$ingredient->id);

        $response->assertStatus(403);
    }

    public function test_ingredient_edit_page_for_no_user_is_redirected()
    {
        $ingredient = Ingredient::factory()->create();

        $response = $this->get('/ingredient/'.$ingredient->id);

        $response->assertStatus(403);
    }

    public function test_updating_ingredient_as_admin_with_correct_data()
    {
        $user = User::factory()->has(Role::factory()->state(['name' => 'admin']))->create();
        $ingredient = Ingredient::factory()->create();

        $response = $this->actingAs($user)->post('/ingredient/'.$ingredient->id, $this->requestData);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect('/ingredient/'.$ingredient->id);
    }

    public function test_updating_ingredient_as_admin_with_incorrect_data()
    {
        $user = User::factory()->has(Role::factory()->state(['name' => 'admin']))->create();
        $ingredient = Ingredient::factory()->create();
        $this->requestData['protein'] = NULL;

        $response = $this->actingAs($user)->post('/ingredient/'.$ingredient->id, $this->requestData);

        $response->assertSessionHasErrors('protein');
    }

    public function test_updating_ingredient_as_user_is_forbidden()
    {
        $user = User::factory()->create();
        $ingredient = Ingredient::factory()->create();

        $response = $this->actingAs($user)->post('/ingredient/'.$ingredient->id);

        $response->assertStatus(403);
    }

    public function test_deleting_ingredient_not_assigned_to_recipe_as_admin()
    {
        $user = User::factory()->has(Role::factory()->state(['name' => 'admin']))->create();
        $ingredient = Ingredient::factory()->create();

        $response = $this->actingAs($user)->post('/ingredient/'.$ingredient->id.'/delete');

        $this->assertModelMissing($ingredient);
        $response->assertSee('true');
    }

    public function test_deleting_ingredient_assigned_to_recipe_as_admin_is_forbidden()
    {
        $user = User::factory()->has(Role::factory()->state(['name' => 'admin']))->create();
        $ingredient = Ingredient::factory()->create();
        $recipe = Recipe::factory()->create();
        $recipe->ingredients()->attach($ingredient->id, ['amount' => 100]);

        $response = $this->actingAs($user)->post('/ingredient/'.$ingredient->id.'/delete');

        $this->assertModelExists($ingredient);
        $response->assertJson(['error' => TRUE]);
    }

    public function test_deleting_ingredient_not_assigned_to_recipe_as_user_is_forbidden()
    {
        $user = User::factory()->create();
        $ingredient = Ingredient::factory()->create();

        $response = $this->actingAs($user)->post('/ingredient/'.$ingredient->id.'/delete');

        $response->assertStatus(403);
    }

    public function test_searching_for_ingredient_returns_results_for_user()
    {
        $user = User::factory()->create();
        $ingredient = Ingredient::factory()->create(['name' => 'Delicious ingredient']);

        $response = $this->actingAs($user)->get('/ingredient-autocomplete', ['name' => 'ingr']);

        $response->assertJsonFragment(['id' => $ingredient->id, 'name' => 'Delicious ingredient']);
    }

    public function test_searching_for_ingredient_for_non_user_is_forbidden()
    {
        Ingredient::factory()->create(['name' => 'Delicious ingredient']);

        $response = $this->get('/ingredient-autocomplete', ['name' => 'ingr']);

        $response->assertRedirect('/login');
    }
}
