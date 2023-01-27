<?php

namespace Tests\Feature\User;

use App\Models\Diet;
use App\Models\DietMealDivision;
use App\Models\MealEnergyDivision;
use App\Models\Profile;
use App\Models\Recipe;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $diet = Diet::factory()->create();
        $tags[] = Tag::factory()->create(['id' => 1]);
        $tags[] = Tag::factory()->create(['id' => 2]);
        $tags[] = Tag::factory()->create(['id' => 3]);
        $tags[] = Tag::factory()->create(['id' => 4]);
        foreach ($tags as $tag) {
            $tagList[] = $tag->id;
        }
        $recipes = Recipe::factory()->count(4)->create();
        foreach ($recipes as $recipe) {
            $recipe->tags()->attach($tagList);
        }
        $oldMealDiv = DietMealDivision::factory()->create(['meals_count' => 4]);
        $this->updateTagsForMealDivs($oldMealDiv, $tagList);
        $newMealDiv = DietMealDivision::factory()->create(['meals_count' => 3]);
        $this->updateTagsForMealDivs($newMealDiv, $tagList);

        $this->requestData = [
            'diet_type' => $diet->id,
            'meals_count' => 3,
            'diet_target' => 1,
            'gender' => 1,
            'birthday' => '1995-09-30',
            'weight' => 70,
            'height' => 165,
            'target_weight' => 60,
            'basic_activity' => 1,
            'sport_activity' => 2
        ];
    }

    protected function updateTagsForMealDivs($dietMealDiv, $tags)
    {
        $i = 0;
        foreach ($dietMealDiv->mealEnergyDivisions as $mealDiv) {
            $mealDiv->tag_id = $tags[$i];
            $mealDiv->save();
            $i++;
        }
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->user);
        unset($this->requestData);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_profile_screen_for_user_without_profile_can_be_rendered()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/profile/new');

        $response->assertStatus(200);
    }

    public function test_profile_screen_for_user_with_profile_can_be_rendered()
    {
        $user = User::factory()->has(Profile::factory())->create();
        $response = $this->actingAs($user)->get('/profile');

        $response->assertStatus(200);
    }

    public function test_profile_screen_without_user_is_redirected()
    {
        $response = $this->get('/profile');

        $response->assertRedirect('/login');
    }

    public function test_saving_new_profile_with_correct_data()
    {
        $user = User::factory()->create();
        $tags = $user->userDiet->getMealsTags();
        $recipes = Recipe::factory()->count(4)->create();
        foreach ($recipes as $recipe) {
            $recipe->tags()->attach($tags);
        }

        $response = $this->actingAs($user)->post('/profile/new', $this->requestData);

        $response->assertStatus(200);
        $response->assertSee('true');
    }

    public function test_saving_new_profile_with_incorrect_data()
    {
        $user = User::factory()->create();
        $this->requestData['weight'] = 30;

        $response = $this->actingAs($user)->post('/profile/new', $this->requestData);

        $response->assertRedirect();
        $response->assertSessionHasErrors('weight');
    }

    public function test_updating_profile_with_correct_data()
    {
        $user = User::factory()->has(Profile::factory())->create();

        $response = $this->actingAs($user)->post('/profile', $this->requestData);

        $response->assertStatus(200);
        $response->assertSee('true');
    }

    public function test_updating_profile_with_incorrect_data()
    {
        $user = User::factory()->create();
        $this->requestData['weight'] = 30;

        $response = $this->actingAs($user)->post('/profile', $this->requestData);

        $response->assertRedirect();
        $response->assertSessionHasErrors('weight');
    }
}
