<?php

namespace Tests\Feature\User;

use App\Models\DietPlan;
use App\Models\Ingredient;
use App\Models\Profile;
use App\Models\Recipe;
use App\Models\ShoppingList;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShoppingListTest extends TestCase
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
    public function test_shopping_list_without_products_can_be_rendered_with_user()
    {
        $response = $this->actingAs($this->user)->get('/shopping-list');

        $response->assertStatus(200);
    }

    public function test_shopping_list_with_products_can_be_rendered_with_user()
    {
        ShoppingList::factory()
                    ->has(Ingredient::factory())
                    ->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)->get('/shopping-list');

        $response->assertStatus(200);
    }

    public function test_shopping_list_screen_without_user_is_redirected()
    {
        $response = $this->get('/shopping-list');

        $response->assertRedirect('/login');
    }

    public function test_updating_shopping_list_for_period_with_recipes_as_user()
    {
        $recipe = Recipe::factory()->hasAttached(Ingredient::factory(), ['amount' => 100])
                        ->create();
        $dietPlan = DietPlan::factory()->create([
            'date_on' => '2022-09-30',
            'user_id' => $this->user->id
        ]);
        $dietPlan->meals[0]->recipe_id = $recipe->id;
        $dietPlan->meals[0]->save();

        $response = $this->actingAs($this->user)
                        ->post(
                            '/shopping-list',
                            [
                                'date_from' => '2022-09-30',
                                'date_to' => '2022-09-30'
                            ]);

        $this->assertDatabaseCount(ShoppingList::class, 1);
        $response->assertRedirect('/shopping-list');
    }

    public function test_updating_shopping_list_for_period_without_recipes_as_user()
    {
        $response = $this->actingAs($this->user)
            ->post(
                '/shopping-list',
                [
                    'date_from' => '2022-09-30',
                    'date_to' => '2022-09-30'
                ]);

        $this->assertDatabaseCount(ShoppingList::class, 0);
        $response->assertRedirect('/shopping-list');
    }

    public function test_updating_shopping_list_as_guest_is_redirected()
    {
        $response = $this->post('/shopping-list');

        $response->assertRedirect('/login');
    }

    public function test_changing_ingredient_amount_on_shopping_list_by_user()
    {
        $ingredient = Ingredient::factory()->create();
        $shoppingList = ShoppingList::factory()->create([
            'user_id' => $this->user->id,
            'ingredient_id' => $ingredient->id,
            'amount' => 100
        ]);

        $response = $this->actingAs($this->user)->put('shopping-list/update', ['id' => $shoppingList->id, 'amount' => 105]);

        $response->assertSee('true');
        $shoppingList = $shoppingList->fresh();
        $this->assertEquals(105, $shoppingList->amount);
    }

    public function test_changing_non_existing_ingredient_amount_on_shopping_list_by_user_throws_error()
    {
        $response = $this->actingAs($this->user)->put('shopping-list/update', ['id' => 1, 'amount' => 105]);

        $response->assertStatus(200);
        $response->assertSee('false');
    }

    public function test_changing_ingredient_amount_on_shopping_list_by_guest_is_redirected()
    {
        $response = $this->put('shopping-list/update');

        $response->assertRedirect('/login');
    }

    public function test_removing_shopping_list_element_by_user()
    {
        $ingredient = Ingredient::factory()->create();
        $shoppingList = ShoppingList::factory()->create([
            'user_id' => $this->user->id,
            'ingredient_id' => $ingredient->id,
            'amount' => 100
        ]);

        $response = $this->actingAs($this->user)->delete('shopping-list/delete', ['id' => $shoppingList->id]);

        $response->assertSee('true');
        $this->assertModelMissing($shoppingList);
    }

    public function test_removing_shopping_list_element_by_guest_is_redirected()
    {
        $response = $this->delete('shopping-list/delete');

        $response->assertRedirect('/login');
    }
}
