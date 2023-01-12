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
//    public function test_handle_with_a_date_and_a_user_given()
//    {
//        $user = User::factory()->create();
//        $tags = $user->userDiet->getMealsTags();
//        $recipes = Recipe::factory()->has(Tag::factory())->count(4)->create();
//        foreach ($recipes as $recipe) {
//            $recipe->tags()->attach($tags);
//        }
//
//        $generateDietPlan = new GenerateDietPlan('2022-09-30');
//        $generateDietPlan->handle($user);
//
//        $this->assertDatabaseCount('diet_plans', 1);
//        $this->assertDatabaseCount('meals', 4);
//    }
//
//    public function test_handle_with_a_date_but_without_a_user_given()
//    {
//        $users = User::factory()->count(2)->create();
//        $tags = [];
//        foreach ($users as $user) {
//            $tags = array_merge($tags, $user->userDiet->getMealsTags());
//        }
//        $recipes = Recipe::factory()->has(Tag::factory())->count(4)->create();
//        foreach ($recipes as $recipe) {
//            $recipe->tags()->attach($tags);
//        }
//        $generateDietPlan = new GenerateDietPlan('2022-09-30');
//
//        $generateDietPlan->handle();
//
//        $this->assertDatabaseCount('diet_plans', 2);
//        $this->assertDatabaseCount('meals', 8);
//    }
//
//    public function test_handle_without_a_date_but_with_a_user_given()
//    {
//        $user = User::factory()->create();
//        $tags = $user->userDiet->getMealsTags();
//        $recipes = Recipe::factory()->has(Tag::factory())->count(4)->create();
//        foreach ($recipes as $recipe) {
//            $recipe->tags()->attach($tags);
//        }
//        $generateDietPlan = new GenerateDietPlan();
//
//        $generateDietPlan->handle($user);
//
//        $this->assertDatabaseCount('diet_plans', 1);
//        $this->assertDatabaseCount('meals', 4);
//    }
//
//    public function test_handle_without_a_date_and_without_a_user_given()
//    {
//        $users = User::factory()->count(2)->create();
//        $tags = [];
//        foreach ($users as $user) {
//            $tags = array_merge($tags, $user->userDiet->getMealsTags());
//        }
//        $recipes = Recipe::factory()->has(Tag::factory())->count(4)->create();
//        foreach ($recipes as $recipe) {
//            $recipe->tags()->attach($tags);
//        }
//
//        GenerateDietPlan::dispatch();
//
//        $this->assertDatabaseCount('diet_plans', 2);
//        $this->assertDatabaseCount('meals', 8);
//    }
}
