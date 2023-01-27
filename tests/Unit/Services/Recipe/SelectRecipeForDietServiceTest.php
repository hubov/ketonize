<?php

namespace Tests\Unit\Services\Recipe;

use App\Models\Recipe;
use App\Models\Tag;
use App\Models\UserDiet;
use App\Repositories\Interfaces\RecipeRepositoryInterface;
use App\Services\Recipe\SelectRecipeForDietService;
use PHPUnit\Framework\TestCase;

class SelectRecipeForDietServiceTest extends TestCase
{
    public $userDiet;
    public $tags;
    public $recipe;
    public $recipeAttributes;
    public $ignoreRecipes;
    public $recipeRepository;
    public $selectRecipeForDietService;

    public function setUp(): void
    {
        $this->userDiet = $this->createStub(UserDiet::class);
        $this->tags = [new Tag()];
        $this->recipe = new Recipe();
        $this->recipeAttributes = [
            'protein_ratio' => [0, 0],
            'carbohydrate_ratio' => [0, 0]
        ];
        $this->ignoreRecipes = [];

        $this->recipeRepository = $this->createMock(RecipeRepositoryInterface::class);

        $this->selectRecipeForDietService = new SelectRecipeForDietService($this->recipeRepository);
    }

    /** @test */
    public function returns_recipe()
    {
        $this->recipeRepository
            ->expects($this->once())
            ->method('getOneByAttributesAndTagsWithoutIds')
            ->with(
                $this->recipeAttributes,
                $this->tags,
                $this->ignoreRecipes
            )->willReturn($this->recipe);

        $this->assertEquals(
            $this->recipe,
            $this->selectRecipeForDietService
                ->setUserDiet($this->userDiet)
                ->setTags($this->tags)
                ->get()
        );
    }
}
