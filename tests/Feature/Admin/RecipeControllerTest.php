<?php

namespace Tests\Feature\Admin;

use App\Models\Ingredient;
use App\Models\Profile;
use App\Models\Recipe;
use App\Models\Role;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class RecipeControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->has(Profile::factory())->has(Role::factory()->state(['name' => 'admin']))->create();
    }

    /** @test */
    public function single_recipe_screen_for_admin_can_be_rendered()
    {
        $recipe = Recipe::factory()->create();
        $response = $this->actingAs($this->user)->get('/recipe/'.$recipe->slug);

        $response->assertViewHas('admin', TRUE);
        $response->assertStatus(200);
    }

    /**
     * @test
     * @dataProvider provideRecipeData
     */
    public function adding_new_recipe_by_admin($providedData)
    {
        [$requestData, $expectedResult] = $providedData();

        $request = $this->actingAs($this->user)->post('/recipes', $requestData);

        $request->assertRedirect($expectedResult['uri']);
        $request->assertLocation($expectedResult['uri']);
        $this->followRedirects($request)
            ->assertSee($expectedResult['name'])
            ->assertSee($expectedResult['description']);
//            ->assertSee($expectedResult['ingredient']);
    }

    /**
     * @test
     * @dataProvider provideRecipeData
     */
    public function updating_recipe_by_admin($providedData)
    {
        [$requestData, $expectedResult] = $providedData();

        $recipe = Recipe::factory()->hasAttached(Ingredient::factory(), ['amount' => '100'])->has(Tag::factory())->create();

        $request = $this->actingAs($this->user)->put('/recipe/'.$recipe->slug.'/edit', $requestData);

        $request->assertRedirect('/recipe/recipe-1');
        $request->assertLocation('/recipe/recipe-1');
        $this->followRedirects($request)
            ->assertSee($requestData['recipe']['name'])
            ->assertSee($requestData['recipe']['description']);
    }

    protected function getRequestData()
    {
        $ingredient = Ingredient::factory()->create();
        $tag = Tag::factory()->create();
        $amount = 100;

        $requestDataWithoutImage = [
            'recipe' => [
                'name' => 'Recipe #1',
                'slug' => 'recipe-1',
                'description' => 'Some recipe description.',
                'preparation_time' => 10,
                'cooking_time' => 5
            ],
            'ingredients' => [
                [
                    'id' => $ingredient->id,
                    'quantity' => $amount
                ]
            ],
            'tags' => [$tag->id],
            'ingredientsNames' => [
                [
                    'name' => $ingredient->name
                ]
            ]
        ];

        return $requestDataWithoutImage;
    }

    public function provideRecipeData()
    {
        return
            [
                'without image' => [ // data set 1
                    function () {
                        $requestData = $this->getRequestData();

                        return [
                            $requestData,
                            [
                                'uri' => '/recipe/' . $requestData['recipe']['slug'],
                                'name' => $requestData['recipe']['name'],
                                'description' => $requestData['recipe']['description'],
                                'ingredient' => $requestData['ingredientsNames'][0]['name']
                            ]
                        ];
                    }
                ],
                'with an image' => [ // data set 2
                    function () {
                        $requestData = $this->getRequestData();
                        $image = UploadedFile::fake()->image('recipe-img.jpg', 2560, 1)->size(1950);

                        return [
                            array_merge_recursive($requestData, ['recipe' => ['image' => $image]]),
                            [
                                'uri' => 'recipe/recipe-1',
                                'name' => 'Recipe #1',
                                'description' => 'Some recipe description.',
                                'ingredient' => $requestData['ingredientsNames'][0]['name']
                            ]
                        ];
                    }
                ]
            ];
    }
}
