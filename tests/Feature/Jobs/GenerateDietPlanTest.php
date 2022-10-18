<?php

namespace Tests\Feature\Jobs;

use App\Jobs\GenerateDietPlan;
use App\Models\Recipe;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GenerateDietPlanTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_handle_with_a_date_and_a_user_given()
    {
        $user = User::factory()->create();
        Tag::factory()->create(['id' => 1]);
        Tag::factory()->create(['id' => 2]);
        Tag::factory()->create(['id' => 3]);
        Tag::factory()->create(['id' => 4]);
        $recipes = Recipe::factory()->has(Tag::factory())->count(4)->create();
        foreach ($recipes as $recipe) {
            $recipe->tags()->attach([1, 2, 3, 4]);
        }
        $generateDietPlan = new GenerateDietPlan('2022-09-30');

        $generateDietPlan->handle($user);

        $this->assertDatabaseCount('diet_plans', 4);
    }

    public function test_handle_with_a_date_but_without_a_user_given()
    {
        $user = User::factory()->count(2)->create();
        Tag::factory()->create(['id' => 1]);
        Tag::factory()->create(['id' => 2]);
        Tag::factory()->create(['id' => 3]);
        Tag::factory()->create(['id' => 4]);
        $recipes = Recipe::factory()->has(Tag::factory())->count(4)->create();
        foreach ($recipes as $recipe) {
            $recipe->tags()->attach([1, 2, 3, 4]);
        }
        $generateDietPlan = new GenerateDietPlan('2022-09-30');

        $generateDietPlan->handle();

        $this->assertDatabaseCount('diet_plans', 8);
    }

    public function test_handle_without_a_date_but_with_a_user_given()
    {
        $user = User::factory()->create();
        Tag::factory()->create(['id' => 1]);
        Tag::factory()->create(['id' => 2]);
        Tag::factory()->create(['id' => 3]);
        Tag::factory()->create(['id' => 4]);
        $recipes = Recipe::factory()->has(Tag::factory())->count(4)->create();
        foreach ($recipes as $recipe) {
            $recipe->tags()->attach([1, 2, 3, 4]);
        }
        $generateDietPlan = new GenerateDietPlan();

        $generateDietPlan->handle($user);

        $this->assertDatabaseCount('diet_plans', 4);
    }

    public function test_handle_without_a_date_and_without_a_user_given()
    {
        $user = User::factory()->count(2)->create();
        Tag::factory()->create(['id' => 1]);
        Tag::factory()->create(['id' => 2]);
        Tag::factory()->create(['id' => 3]);
        Tag::factory()->create(['id' => 4]);
        $recipes = Recipe::factory()->has(Tag::factory())->count(4)->create();
        foreach ($recipes as $recipe) {
            $recipe->tags()->attach([1, 2, 3, 4]);
        }
        $generateDietPlan = new GenerateDietPlan();

        $generateDietPlan->handle();

        $this->assertDatabaseCount('diet_plans', 8);
    }
}