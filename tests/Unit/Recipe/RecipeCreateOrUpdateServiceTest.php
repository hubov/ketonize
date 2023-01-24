<?php

namespace Tests\Unit\Recipe;

use App\Models\Recipe;
use App\Repositories\Interfaces\IngredientRepositoryInterface;
use App\Repositories\Interfaces\RecipeRepositoryInterface;
use App\Repositories\Interfaces\TagRepositoryInterface;
use App\Services\Interfaces\Recipe\RelateIngredientsToRecipeInterface;
use App\Services\Recipe\RecipeCreateOrUpdateService;
use PHPUnit\Framework\TestCase;

class RecipeCreateOrUpdateServiceTest extends TestCase
{
    public $attributes = [];
    public $recipeAttributes = [];
    public $ingredientsAttributes = [];
    public $tagsAttributes = [];
    public $slug;
    public $recipe;
    public $recipeRepository;
    public $ingredientRepository;
    public $tagRepository;
    public $relateIngredientsToRecipe;
    public $recipeCreateOrUpdateService;

    public function setUp(): void
    {
        $this->attributes = [
            'name' => 'Delicious recipe',
            'image' => 'default',
            'description' => '1. Mix all ingredients.',
            'preparation_time' => 5,
            'cooking_time' => 1,
            'ids' => [1],
            'quantity' => [100],
            'tags' => [1]
        ];
        $this->recipeAttributes = [
            'name' => $this->attributes['name'],
            'image' => $this->attributes['image'],
            'description' => $this->attributes['description'],
            'preparation_time' => $this->attributes['preparation_time'],
            'cooking_time' => $this->attributes['cooking_time']
        ];
        $this->ingredientsAttributes = [
            'ids' => $this->attributes['ids'],
            'quantity' => $this->attributes['quantity']
        ];
        $this->tagsAttributes = $this->attributes['tags'];
        $this->slug = 'delicious-recipe';
        $this->recipe = new Recipe();
        $this->recipe->id = 1;
        $this->recipe->slug = $this->slug;

        $this->recipeRepository = $this->createMock(RecipeRepositoryInterface::class);
        $this->ingredientRepository = $this->createMock(IngredientRepositoryInterface::class);
        $this->tagRepository  = $this->createMock(TagRepositoryInterface::class);
        $this->relateIngredientsToRecipe = $this->createMock(RelateIngredientsToRecipeInterface::class);

        $this->recipeCreateOrUpdateService = new RecipeCreateOrUpdateService(
            $this->recipeRepository,
            $this->ingredientRepository,
            $this->tagRepository,
            $this->relateIngredientsToRecipe
        );

        $this->recipeRepository
            ->expects($this->atLeastOnce())
            ->method('syncTags')
            ->with($this->recipe->id, $this->tagsAttributes);

        $this->relateIngredientsToRecipe
            ->expects($this->atLeastOnce())
            ->method('setRecipe')
            ->with($this->recipe->id)
            ->willReturnSelf();
        $this->relateIngredientsToRecipe
            ->expects($this->atLeastOnce())
            ->method('addIngredient')
            ->with(1);
        $this->relateIngredientsToRecipe
            ->expects($this->atLeastOnce())
            ->method('sync');
    }

    /** @test */
    public function it_creates_recipe_with_complete_data()
    {
        $this->recipeRepository
            ->expects($this->once())
            ->method('create')
            ->with($this->recipeAttributes)
            ->willReturn($this->recipe);

        $result = $this->recipeCreateOrUpdateService
            ->perform($this->attributes);

        $this->assertEquals($result, $this->recipe);
    }

    /** @test */
    public function it_updates_recipe_with_complete_data()
    {
        $this->recipeRepository
            ->expects($this->once())
            ->method('updateBySlug')
            ->with($this->recipe->slug, $this->recipeAttributes)
            ->willReturn($this->recipe);

        $result = $this->recipeCreateOrUpdateService
            ->perform($this->attributes, $this->recipe->slug);

        $this->assertEquals($result, $this->recipe);
    }
}
