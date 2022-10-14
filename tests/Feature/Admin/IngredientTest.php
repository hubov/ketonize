<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IngredientTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_ingredient_listing_screen_can_be_rendered_without_user()
    {
        $response = $this->get('/ingredients');

        $response->assertStatus(302);
    }

    public function test_ingredient_listing_screen_can_be_rendered_with_user()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/ingredients');

        $response->assertStatus(200);
    }
}
