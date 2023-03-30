<?php

namespace Tests\Unit\Services\Recipe;

use App\Models\CustomIngredient;
use App\Models\Ingredient;
use App\Models\Recipe;
use App\Models\RecipeIdea;
use App\Repositories\Interfaces\CustomIngredientRepositoryInterface;
use App\Repositories\Interfaces\IngredientRepositoryInterface;
use App\Services\Recipe\RelateIngredientsToRecipeService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use PHPUnit\Framework\TestCase;

class RelateIngredientsToRecipeServiceTest extends TestCase
{
    protected $ingredient;
    protected $customIngredient;
    protected $ingredientRepository;
    protected $customIngredientRepository;
    protected $relateIngredientsToRecipe;
    protected $recipe;
    protected $recipeIdea;
    protected $relatedIngredients;
    protected $relatedCustomIngredients;

    protected function setUp(): void
    {
        $this->ingredient = new Ingredient();
        $this->ingredient->id = 1;
        $this->customIngredient = new CustomIngredient();
        $this->customIngredient->id = 100;
        $this->recipe = $this->createPartialMock(Recipe::class, ['ingredients', 'customIngredients']);

        $this->ingredientRepository = $this->createMock(IngredientRepositoryInterface::class);
        $this->customIngredientRepository = $this->createMock(CustomIngredientRepositoryInterface::class);

        $this->relateIngredientsToRecipe = new RelateIngredientsToRecipeService($this->ingredientRepository, $this->customIngredientRepository);
    }

    /** @test */
    public function adds_ingredient_by_name(): void
    {
        $this->ingredientRepository
            ->expects($this->once())
            ->method('getByName')
            ->with('Tomato')
            ->willReturn($this->ingredient);

        $this->customIngredientRepository
            ->expects($this->never())
            ->method('getOrCreateForUserByName');

        $this->relateIngredientsToRecipe
            ->setRecipe($this->recipe)
            ->setUser(1)
            ->addIngredientByName('Tomato', 100, 1);
    }

    /** @test */
    public function adds_custom_ingredient_by_name(): void
    {
        $this->ingredientRepository
            ->expects($this->once())
            ->method('getByName')
            ->with('Tomato')
            ->willThrowException(new ModelNotFoundException());

        $this->customIngredientRepository
            ->expects($this->once())
            ->method('getOrCreateForUserByName')
            ->with(1, ['name' => 'Tomato', 'unit' => 1])
            ->willReturn($this->customIngredient);

        $this->relateIngredientsToRecipe
            ->setRecipe($this->recipe)
            ->setUser(1)
            ->addIngredientByName('Tomato', 100, 1);
    }

    /** @test */
    public function synces_ingredients_for_recipe()
    {
        $this->ingredientRepository
            ->expects($this->once())
            ->method('getByName')
            ->with('Tomato')
            ->willReturn($this->ingredient);

        $this->customIngredientRepository
            ->expects($this->never())
            ->method('getOrCreateForUserByName');

        $this->ingredientsSyncing();

        $this->relateIngredientsToRecipe
            ->setRecipe($this->recipe)
            ->setUser(1)
            ->addIngredientByName('Tomato', 100, 1);
        $this->relateIngredientsToRecipe
            ->sync();
    }

    /** @test */
    public function synces_ingredients_for_recipe_idea()
    {
        $this->ingredientRepository
            ->expects($this->once())
            ->method('getByName')
            ->with('Tomato')
            ->willReturn($this->ingredient);

        $this->customIngredientRepository
            ->expects($this->never())
            ->method('getOrCreateForUserByName');

        $this->recipe = $this->createPartialMock(RecipeIdea::class, ['ingredients', 'customIngredients']);

        $this->ingredientsSyncing();

        $this->relateIngredientsToRecipe
            ->setRecipe($this->recipe)
            ->setUser(1)
            ->addIngredientByName('Tomato', 100, 1);
        $this->relateIngredientsToRecipe
            ->sync();
    }

    protected function ingredientsSyncing()
    {
        $this->relatedIngredients = $this->createMock(BelongsToMany::class);
        $this->relatedIngredients
            ->expects($this->once())
            ->method('sync')
            ->withAnyParameters();

        $this->relatedCustomIngredients = $this->createMock(BelongsToMany::class);
        $this->relatedCustomIngredients
            ->expects($this->once())
            ->method('sync')
            ->withAnyParameters();

        $this->recipe
            ->expects($this->once())
            ->method('ingredients')
            ->willReturn($this->relatedIngredients);
        $this->recipe
            ->expects($this->once())
            ->method('customIngredients')
            ->willReturn($this->relatedCustomIngredients);
    }
}
