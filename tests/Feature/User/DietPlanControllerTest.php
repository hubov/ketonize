<?php

namespace Tests\Feature\User;

use App\Exceptions\DateOlderThanAccountException;
use App\Exceptions\DietPlanOutOfDateRangeException;
use App\Exceptions\DietPlanUnderConstructionException;
use App\Models\DietPlan;
use App\Models\Profile;
use App\Models\Recipe;
use App\Models\Tag;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DietPlanControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $today;
    protected $yesterday;
    protected $tommorow;
    protected $fifteenDaysAgo;
    protected $in30Days;

    protected function setUp(): void
    {
        parent::setUp();

        $dateFormat = 'Y-m-d';

        $this->user = User::factory()->has(Profile::factory())->create();
        $this->today = Carbon::today()->format($dateFormat);
        $this->fifteenDaysAgo = Carbon::today()->subDays(15)->format($dateFormat);
        $this->in30Days = Carbon::today()->addDays(30)->format($dateFormat);
        $this->yesterday = Carbon::yesterday()->format($dateFormat);
        $this->tommorow = Carbon::tomorrow()->format($dateFormat);
    }

    protected function tearDown(): void
    {
        unset($this->user);

        parent::tearDown();
    }

    /** @test */
    public function diet_plan_screen_without_meals_can_be_rendered_and_throws_exceptions()
    {
        // without date
        $response = $this->actingAs($this->user)->get('/dashboard');
        $response->assertStatus(200);
        $response->assertSeeText((new DietPlanUnderConstructionException())->getMessage());

        //with a date
        $response = $this->actingAs($this->user)->get('/dashboard/' . $this->fifteenDaysAgo);
        $response->assertStatus(200);
        $response->assertSeeText((new DietPlanOutOfDateRangeException())->getMessage());

        //with a date before account activation
        $response = $this->actingAs($this->user)->get('/dashboard/' . $this->yesterday);
        $response->assertStatus(200);
        $response->assertSeeText((new DateOlderThanAccountException())->getMessage());
    }

    /** @test */
    public function diet_plan_with_meals_without_date_given()
    {
        DietPlan::factory()->count(4)->create([
            'user_id' => $this->user,
            'date_on' => $this->today
        ]);

        $response = $this->actingAs($this->user)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSeeText(['change-meal', 'diet-meal', 'meal-tags']);
    }

    /** @test */
    public function diet_plan_with_meals_with_date_given()
    {
        DietPlan::factory()->count(4)->create([
            'user_id' => $this->user,
            'date_on' => $this->tommorow
        ]);

        $response = $this->actingAs($this->user)->get('/dashboard/' . $this->tommorow);

        $response->assertStatus(200);
        $response->assertSeeText(['change-meal', 'diet-meal', 'meal-tags']);
    }

    /** @test */
    public function diet_plan_generate_user_signed_in()
    {
        $tags = $this->user->userDiet->getMealsTags();
        $recipes = Recipe::factory()->has(Tag::factory())->count(4)->create();
        foreach ($recipes as $recipe) {
            $recipe->tags()->attach($tags);
        }

        $response = $this->actingAs($this->user)->get('/dashboard/generate/' . $this->tommorow);
        $this->assertDatabaseCount('diet_plans', 1);
        $this->assertDatabaseCount('meals', 4);

        $response->assertRedirect('/dashboard/' . $this->tommorow);
    }

    /** @test */
    public function diet_plan_generate_user_not_signed_in()
    {
        Recipe::factory()->has(Tag::factory())->count(4)->create();

        $response = $this->get('/dashboard/generate/2022-09-30');
        $this->assertDatabaseCount('diet_plans', 0);
        $this->assertDatabaseCount('meals', 0);

        $response->assertRedirect('/login');
    }

    /** @test */
    public function diet_plan_update_recipe()
    {
        DietPlan::factory()->create(['user_id' => $this->user->id, 'date_on' => $this->today]);
        $recipe = Recipe::factory()->has(Tag::factory())->create();

        $response = $this->actingAs($this->user)->post('/diet/update', ['date' => $this->today, 'meal' => 1, 'slug' => $recipe->slug]);

        $response->assertStatus(200);
        $response->assertSeeText($recipe->id);
        $this->assertDatabaseCount('meals', 1);
    }

    /** @test */
    public function it_returns_false_or_true_if_diet_plan_is_not_ready_or_ready()
    {
        // diet plan is not ready
        $response = $this->actingAs($this->user)->post('/dashboard/is-ready', ['time' => Carbon::now()->getTimestamp()]);

        $response->assertSeeText('false');

        // diet plan is ready
        $now = Carbon::now()->getTimestamp();
        DietPlan::factory()->create(['user_id' => $this->user->id, 'date_on' => $this->today]);
        $response = $this->actingAs($this->user)->post('/dashboard/is-ready', ['time' => $now]);

        $response->assertSeeText('true');
    }
}
