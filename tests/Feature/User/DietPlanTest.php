<?php

namespace Tests\Feature\User;

use App\Models\DietPlan;
use App\Models\Recipe;
use App\Models\Tag;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DietPlanTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_diet_plan_screen_can_be_rendered_without_date()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
    }

    public function test_diet_plan_screen_can_be_rendered_with_date()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/dashboard/2022-09-29');

        $response->assertStatus(200);
    }

    public function test_diet_plan_without_meals()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/dashboard/2022-09-29');

        $response->assertStatus(200);
    }

    public function test_diet_plan_with_meals()
    {
        $user = User::factory()->create();
        $meals = DietPlan::factory()->count(4)->create([
            'user_id' => $user,
            'date_on' => '2022-09-29'
        ]);
        $response = $this->actingAs($user)->get('/dashboard/2022-09-29');

        $response->assertStatus(200);
    }

    public function test_diet_plan_generate_user_signed_in()
    {
        $user = User::factory()->create();
        $recipes = Recipe::factory()->has(Tag::factory())->count(4)->create();

        $response = $this->actingAs($user)->get('/dashboard/generate/2022-09-30');

        $response->assertStatus(302);
    }

    public function test_diet_plan_generate_user_not_signed_in()
    {
        $recipes = Recipe::factory()->has(Tag::factory())->count(4)->create();

        $response = $this->get('/dashboard/generate/2022-09-30');

        $response->assertRedirect('/login');
    }
}
