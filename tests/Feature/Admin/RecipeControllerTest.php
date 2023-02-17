<?php

namespace Tests\Feature\Admin;

use App\Models\Ingredient;
use App\Models\Profile;
use App\Models\Recipe;
use App\Models\Role;
use App\Models\Tag;
use App\Models\User;
use App\Services\File\Factories\SaverFactory;
use App\Services\Image\Factories\ImageFactory;
use App\Services\Image\RecipeImageProcessor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Tests\TestCase;

class RecipeControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $recipeImageProcessor;
    protected $publicCovers;
    protected $publicThumbnails;
    protected $localOriginals;
    protected $covers;
    protected $thumbnails;
    protected $originals;
    protected $fileFormats;

    protected function setUp(): void
    {
        parent::setUp();

        $this->recipeImageProcessor = new RecipeImageProcessor(
            new ImageManager(),
            new ImageFactory(),
            new SaverFactory()
        );
        $this->publicCovers = $this->recipeImageProcessor::STORAGE_DISK_COVERS;
        $this->publicThumbnails = $this->recipeImageProcessor::STORAGE_DISK_THUMBNAILS;
        $this->localOriginals = $this->recipeImageProcessor::STORAGE_DISK_LOCAL;

        $this->user = User::factory()->has(Profile::factory())->has(Role::factory()->state(['name' => 'admin']))->create();

        $this->fileFormats = [
            'jpg',
            'webp'
        ];
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
        $this->getDisks();

        [$expectedResult, $requestData] = $providedData();

        $request = $this->actingAs($this->user)->post('/recipes', $requestData);

        $request->assertRedirect($expectedResult['uri']);
        $request->assertLocation($expectedResult['uri']);
        $this->followRedirects($request)
            ->assertSee($expectedResult['name'])
            ->assertSee($expectedResult['description']);
        $this->assertEquals($expectedResult['originals'], count($this->originals->files()));
        $this->assertEquals($expectedResult['covers'], count($this->covers->files()));
        $this->assertEquals($expectedResult['thumbnails'], count($this->thumbnails->files()));
        if (isset($expectedResult['img_name'])) {
            $this->assertEquals($expectedResult['img_name'], substr($this->originals->files()[0], 0, 9));
            $this->assertEquals('.jpg', substr($this->originals->files()[0], -4));

            $this->assertEquals($expectedResult['img_name'], substr($this->covers->files()[0], 0, 9));
            $this->assertEquals($expectedResult['img_name'], substr($this->thumbnails->files()[0], 0, 9));
            foreach ($this->recipeImageProcessor->getFileFormats() as $order => $format) {
                $offset = strlen($format) + 1;
                $this->assertEquals('.' . $format, substr($this->covers->files()[$order], -$offset));
                $this->assertEquals('.' . $format, substr($this->thumbnails->files()[$order], -$offset));
            }
        }
    }

    /**
     * @test
     * @dataProvider provideRecipeData
     */
    public function updating_recipe_by_admin($providedData)
    {
        $this->getDisks();

        [$expectedResult, $requestData] = $providedData();

        $recipe = Recipe::factory()->hasAttached(Ingredient::factory(), ['amount' => '100'])->has(Tag::factory())->create();

        $request = $this->actingAs($this->user)->put('/recipe/'.$recipe->slug.'/edit', $requestData);

        $request->assertRedirect('/recipe/recipe-1');
        $request->assertLocation('/recipe/recipe-1');
        $this->followRedirects($request)
            ->assertSee($requestData['recipe']['name'])
            ->assertSee($requestData['recipe']['description']);
        $this->assertEquals($expectedResult['originals'], count($this->originals->files()));
        $this->assertEquals($expectedResult['covers'], count($this->covers->files()));
        $this->assertEquals($expectedResult['thumbnails'], count($this->thumbnails->files()));
        if (isset($expectedResult['img_name'])) {
            $this->assertEquals($expectedResult['img_name'], substr($this->originals->files()[0], 0, 9));
            $this->assertEquals('.jpg', substr($this->originals->files()[0], -4));

            $this->assertEquals($expectedResult['img_name'], substr($this->covers->files()[0], 0, 9));
            $this->assertEquals($expectedResult['img_name'], substr($this->thumbnails->files()[0], 0, 9));
            foreach ($this->recipeImageProcessor->getFileFormats() as $order => $format) {
                $offset = strlen($format) + 1;
                $this->assertEquals('.' . $format, substr($this->covers->files()[$order], -$offset));
                $this->assertEquals('.' . $format, substr($this->thumbnails->files()[$order], -$offset));
            }
        }
    }

    protected function getDisks()
    {
        $this->covers = Storage::fake($this->publicCovers);
        $this->thumbnails = Storage::fake($this->publicThumbnails);
        $this->originals = Storage::fake($this->localOriginals);
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
                            [
                                'uri' => '/recipe/' . $requestData['recipe']['slug'],
                                'name' => $requestData['recipe']['name'],
                                'description' => $requestData['recipe']['description'],
                                'ingredient' => $requestData['ingredientsNames'][0]['name'],
                                'originals' => 0,
                                'covers' => 0,
                                'thumbnails' => 0
                            ],
                            $requestData
                        ];
                    }
                ],
                'with an image' => [ // data set 2
                    function () {
                        $requestData = $this->getRequestData();
                        $image = UploadedFile::fake()->image('recipe-img.jpg', 2560, 1)->size(1950);

                        return [
                            [
                                'uri' => 'recipe/recipe-1',
                                'name' => 'Recipe #1',
                                'description' => 'Some recipe description.',
                                'ingredient' => $requestData['ingredientsNames'][0]['name'],
                                'originals' => 1,
                                'covers' => 2,
                                'thumbnails' => 2,
                                'img_name' => $requestData['recipe']['slug'] . '-'
                            ],
                            array_merge_recursive($requestData, ['recipe' => ['image' => $image]])
                        ];
                    }
                ]
            ];
    }
}
