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
    public function adding_new_ingredient_as_admin_with_correct_data()
    {
        $user = User::factory()->has(Role::factory()->state(['name' => 'admin']))->create();

        $response = $this->actingAs($user)->post('/ingredients', $this->requestData);


        $response->assertRedirectContains('/ingredient/');
    }

    /** @test */
    public function adding_new_ingredient_as_admin_with_incorrect_data()
    {
        $user = User::factory()->has(Role::factory()->state(['name' => 'admin']))->create();
        $this->requestData['protein'] = NULL;

        $response = $this->actingAs($user)->post('/ingredients', $this->requestData);

        $response->assertSessionHasErrors('protein');
    }

    /** @test */
    public function adding_new_ingredient_via_ajax_as_admin_with_correct_data()
    {
        $user = User::factory()->has(Role::factory()->state(['name' => 'admin']))->create();

        $response = $this->actingAs($user)->post('/ingredient/new', $this->requestData);

        $response->assertStatus(200);
        $response->assertSee('id');
    }

    /** @test */
    public function adding_new_ingredient_via_ajax_as_admin_with_incorrect_data()
    {
        $user = User::factory()->has(Role::factory()->state(['name' => 'admin']))->create();
        $this->requestData['protein'] = NULL;

        $response = $this->actingAs($user)->post('/ingredient/new', $this->requestData);

        $response->assertSessionHasErrors('protein');
    }

    /** @test */
    public function ingredient_edit_page_is_rendering_for_admin()
    {
        $user = User::factory()->has(Role::factory()->state(['name' => 'admin']))->create();
        $ingredient = Ingredient::factory()->create();

        $response = $this->actingAs($user)->get('/ingredient/'.$ingredient->id);

        $response->assertStatus(200);
    }

    /** @test */
    public function updating_ingredient_as_admin_with_correct_data()
    {
        $user = User::factory()->has(Role::factory()->state(['name' => 'admin']))->create();
        $ingredient = Ingredient::factory()->create();

        $response = $this->actingAs($user)->put('/ingredient/'.$ingredient->id, $this->requestData);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect('/ingredient/'.$ingredient->id);
    }

    /** @test */
    public function updating_ingredient_as_admin_with_incorrect_data()
    {
        $user = User::factory()->has(Role::factory()->state(['name' => 'admin']))->create();
        $ingredient = Ingredient::factory()->create();
        $this->requestData['protein'] = NULL;

        $response = $this->actingAs($user)->put('/ingredient/'.$ingredient->id, $this->requestData);

        $response->assertSessionHasErrors('protein');
    }

    /** @test */
    public function deleting_ingredient_not_assigned_to_recipe_as_admin()
    {
        $user = User::factory()->has(Role::factory()->state(['name' => 'admin']))->create();
        $ingredient = Ingredient::factory()->create();

        $response = $this->actingAs($user)->delete('/ingredient/'.$ingredient->id.'/delete');

        $this->assertModelMissing($ingredient);
        $response->assertStatus(200);
        $response->assertSee('true');
    }

    /** @test */
    public function deleting_ingredient_assigned_to_recipe_as_admin_is_forbidden()
    {
        $user = User::factory()->has(Role::factory()->state(['name' => 'admin']))->create();
        $ingredient = Ingredient::factory()->create();
        $recipe = Recipe::factory()->create();
        $recipe->ingredients()->attach($ingredient->id, ['amount' => 100]);

        $response = $this->actingAs($user)->delete('/ingredient/'.$ingredient->id.'/delete');

        $this->assertModelExists($ingredient);
        $response->assertStatus(403);
        $response->assertJson(['error' => TRUE]);
    }
}
