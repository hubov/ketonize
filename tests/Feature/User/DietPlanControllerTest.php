<?php

namespace Tests\Feature\User;

use App\Exceptions\DateOlderThanAccountException;
use App\Exceptions\DietPlanOutOfDateRangeException;
use App\Exceptions\DietPlanUnderConstruction;
use App\Models\DietPlan;
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

        $this->user = User::factory()->create();
        $this->today = Carbon::today()->format($dateFormat);
        $this->fifteenDaysAgo = Carbon::today()->subDays(15)->format($dateFormat);
        $this->in30Days = Carbon::today()->addDays(30)->format($dateFormat);
        $this->yesterday = Carbon::yesterday()->format($dateFormat);
        $this->tommorow = Carbon::tomorrow()->format($dateFormat);
    }

    /** @test */
    public function diet_plan_screen_without_meals_can_be_rendered()
    {
        // without date
        $response = $this->actingAs($this->user)->get('/dashboard');
        $response->assertStatus(200);
        $response->assertSeeText((new DietPlanUnderConstruction())->getMessage());

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
    public function diet_plan_with_meals()
    {
        $user = User::factory()->create();
        DietPlan::factory()->count(4)->create([
            'user_id' => $user,
            'date_on' => $this->today
        ]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSeeText(['change-meal', 'diet-meal', 'meal-tags']);
    }

    /** @test */
    public function diet_plan_generate_user_signed_in()
    {
        $user = User::factory()->create();
        $tags = $user->userDiet->getMealsTags();
        $recipes = Recipe::factory()->has(Tag::factory())->count(4)->create();
        foreach ($recipes as $recipe) {
            $recipe->tags()->attach($tags);
        }

        $response = $this->actingAs($user)->get('/dashboard/generate/2022-09-30');

        $response->assertRedirect('/dashboard/2022-09-30');
    }

    public function test_diet_plan_generate_user_not_signed_in()
    {
        Recipe::factory()->has(Tag::factory())->count(4)->create();

        $response = $this->get('/dashboard/generate/2022-09-30');

        $response->assertRedirect('/login');
    }

    public function  test_diet_plan_update_recipe()
    {
        $user = User::factory()->create();
        $dietPlan = DietPlan::factory()->create(['user_id' => $user->id, 'date_on' => '2022-11-30']);
        $recipe = Recipe::factory()->has(Tag::factory())->create();

        $response = $this->actingAs($user)->post('/diet/update', ['date' => '2022-11-30', 'meal' => 1, 'slug' => $recipe->slug]);

        $response->assertStatus(200);
        $response->assertSee($recipe->id);
        $this->assertEquals(1, count($dietPlan->meals));
    }
}
