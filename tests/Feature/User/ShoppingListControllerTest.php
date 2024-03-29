<?php

namespace Tests\Feature\User;

use App\Models\DietPlan;
use App\Models\Ingredient;
use App\Models\IngredientCategory;
use App\Models\Profile;
use App\Models\Recipe;
use App\Models\ShoppingList;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShoppingListControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->has(Profile::factory())->create(['id' => 1]);
        IngredientCategory::factory()->create(['id' => 1000]);
    }

    /** @test */
    public function shopping_list_without_products_can_be_rendered_with_user()
    {
        $response = $this->actingAs($this->user)->get('/shopping-list');

        $response->assertStatus(200);
        $response->assertSee('alert-success');
    }

    /** @test */
    public function shopping_list_with_products_can_be_rendered_with_user()
    {
        $shoppingList = ShoppingList::factory()
            ->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)->get('/shopping-list');

        $response->assertStatus(200);
        $response->assertSeeInOrder([$shoppingList->itemable->name, $shoppingList->amount]);
    }

    /** @test */
    public function shopping_list_screen_without_user_is_redirected()
    {
        $response = $this->get('/shopping-list');

        $response->assertRedirect('/login');
    }

    /** @test */
    public function updating_shopping_list_for_period_with_recipes_as_user()
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
        $this->followRedirects($response)
            ->assertDontSee('alert-success')
            ->assertSee($recipe->ingredients->first()->name);
    }

    /** @test */
    public function updating_shopping_list_for_period_without_recipes_as_user()
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
        $this->followRedirects($response)
            ->assertSee('alert-success');
    }

    /** @test */
    public function updating_shopping_list_as_guest_is_redirected()
    {
        $response = $this->post('/shopping-list');

        $response->assertRedirect('/login');
    }

    /** @test */
    public function changing_ingredient_amount_on_shopping_list_by_user()
    {
        $ingredient = Ingredient::factory()->create();
        $shoppingList = ShoppingList::factory()->create([
            'user_id' => $this->user->id,
            'itemable_id' => $ingredient->id,
            'itemable_type' => 'App\Models\Ingredient',
            'amount' => 100
        ]);

        $response = $this->actingAs($this->user)->put('shopping-list/update', ['id' => $shoppingList->id, 'amount' => 105]);

        $response->assertSee('true');
        $shoppingList = $shoppingList->fresh();
        $this->assertEquals(105, $shoppingList->amount);
    }

    /** @test */
    public function changing_non_existing_ingredient_amount_on_shopping_list_by_user_throws_error()
    {
        $response = $this->actingAs($this->user)->put('shopping-list/update', ['id' => 1, 'amount' => 105]);

        $response->assertStatus(200);
        $response->assertSee('false');
    }

    /** @test */
    public function changing_ingredient_amount_on_shopping_list_by_guest_is_redirected()
    {
        $response = $this->put('shopping-list/update');

        $response->assertRedirect('/login');
    }

    /** @test */
    public function removing_shopping_list_element_by_user()
    {
        $ingredient = Ingredient::factory()->create();
        $shoppingList = ShoppingList::factory()->create([
            'user_id' => $this->user->id,
            'itemable_id' => $ingredient->id,
            'amount' => 100
        ]);

        $response = $this->actingAs($this->user)->delete('shopping-list/delete', ['id' => $shoppingList->id]);

        $response->assertSee('true');
        $this->assertModelMissing($shoppingList);
        $this->assertDatabaseCount('shopping_lists', 0);
    }

    /** @test */
    public function removing_shopping_list_element_by_guest_is_redirected()
    {
        ShoppingList::factory()->create();
        $response = $this->delete('shopping-list/delete');

        $response->assertRedirect('/login');
        $this->assertDatabaseCount('shopping_lists', 1);
    }

    /** @test */
    public function trashing_list_element_by_user()
    {
        $ingredient = Ingredient::factory()->create();
        $shoppingList = ShoppingList::factory()->create([
            'user_id' => $this->user->id,
            'itemable_id' => $ingredient->id,
            'amount' => 100
        ]);

        $response = $this->actingAs($this->user)->delete('shopping-list/trash', ['id' => $shoppingList->id]);
        $shoppingList->refresh();

        $response->assertSee('true');
        $this->assertModelExists($shoppingList);
        $this->assertTrue($shoppingList->trashed());
        $this->assertDatabaseCount('shopping_lists', 1);
    }

    /** @test */
    public function restoring_list_element_by_user()
    {
        $ingredient = Ingredient::factory()->create();
        $shoppingList = ShoppingList::factory()->create([
            'user_id' => $this->user->id,
            'itemable_id' => $ingredient->id,
            'amount' => 100
        ]);
        $shoppingList->delete();

        $response = $this->actingAs($this->user)->post('shopping-list/restore', ['id' => $shoppingList->id]);
        $shoppingList->refresh();

        $response->assertSee('true');
        $this->assertModelExists($shoppingList);
        $this->assertFalse($shoppingList->trashed());
        $this->assertDatabaseCount('shopping_lists', 1);
    }

    /**
     * @test
     * @dataProvider ownIngredientsToAddProvider
     */
    public function add_own_ingredient_to_shopping_list($expectedResult, $input)
    {
        $ingredient = $input['model_type']::factory()->create($input['attributes']);

        $response = $this
            ->actingAs($this->user)
            ->post(
                'shopping-list/add',
                [
                    'item_name' => $input['attributes']['name'],
                    'amount' => 100,
                    'unit' => $ingredient->unit->id
                ]
            );

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/json');
        $this->assertDatabaseCount('shopping_lists', 1);
        $this->assertJson($response->content());
        $response->assertJsonStructure(
            [
                'id',
                'amount',
                'itemable_id',
                'itemable_type',
                'user_id'
            ]
        );
    }

    /**
     * @test
     * @dataProvider ownIngredientsToAddProvider
     */
    public function add_own_ingredient_which_is_trashed_to_shopping_list($expectedResult, $input)
    {
        $ingredient = $input['model_type']::factory()->create($input['attributes']);
        $shoppingList = ShoppingList::factory()->create([
            'user_id' => $this->user,
            'itemable_id' => $ingredient->id,
            'itemable_type' => get_class($ingredient),
            'amount' => 50
        ]);
        $shoppingList->delete();

        $response = $this->followingRedirects()
            ->actingAs($this->user)
            ->post(
                'shopping-list/add',
                [
                    'item_name' => $input['attributes']['name'],
                    'amount' => 100,
                    'unit' => $ingredient->unit->id
                ]
            );

        $shoppingList->refresh();

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/json');
        $this->assertJson($response->content());
        $response->assertJsonStructure(
            [
                'id',
                'amount',
                'itemable_id',
                'itemable_type',
                'user_id'
            ]
        );
        $this->assertDatabaseCount('shopping_lists', 1);
        $this->assertFalse($shoppingList->trashed());
        $this->assertEquals(100, $shoppingList->amount);
    }

    /**
     * @test
     * @dataProvider ownIngredientsToAddProvider
     */
    public function add_own_ingredient_which_is_already_in_shopping_list($expectedResult, $input)
    {
        $ingredient = $input['model_type']::factory()->create($input['attributes']);
        $shoppingList = ShoppingList::factory()->create([
            'user_id' => $this->user->id,
            'itemable_id' => $ingredient->id,
            'itemable_type' => get_class($ingredient),
            'amount' => 50
        ]);

        $response = $this->followingRedirects()
            ->actingAs($this->user)
            ->post(
                'shopping-list/add',
                [
                    'item_name' => $input['attributes']['name'],
                    'amount' => 100,
                    'unit' => $ingredient->unit->id
                ]
            );

        $shoppingList->refresh();

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/json');
        $this->assertJson($response->content());
        $response->assertJsonStructure(
            [
                'id',
                'amount',
                'itemable_id',
                'itemable_type',
                'user_id'
            ]
        );
        $this->assertDatabaseCount('shopping_lists', 1);
        $this->assertFalse($shoppingList->trashed());
        $this->assertEquals(150, $shoppingList->amount);
    }

    protected function ownIngredientsToAddProvider()
    {
        return [
            [
                true,
                [
                    'attributes' => [
                        'name' => 'Onion',
                    ],
                    'model_type' => 'App\Models\Ingredient'
                ]
            ],
            [
                true,
                [
                    'attributes' => [
                        'name' => 'Onionxxx',
                        'user_id' => 1
                    ],
                    'model_type' => 'App\Models\CustomIngredient'
                ]
            ]
        ];
    }
}
