<?php

namespace Tests\Unit\Services;

use App\Models\Ingredient;
use App\Models\Recipe;
use App\Repositories\Interfaces\IngredientRepositoryInterface;
use App\Services\IngredientService;
use PHPUnit\Framework\TestCase;

class IngredientServiceTest extends TestCase
{
    public $ingredientRepository;

    public function setUp(): void
    {
        parent::setUp();

        $this->ingredientId = 1;
        $this->ingredient = new Ingredient();
        $this->ingredient->id = $this->ingredientId;

        $this->ingredientRepository = $this->createMock(IngredientRepositoryInterface::class);
    }

    /** @test */
    public function returns_collection_of_slugs_of_related_recipes_if_any_exist()
    {
        $recipe1 = new Recipe();
        $recipe1->slug = 'related-recipe';
        $recipe2 = new Recipe();
        $recipe2->slug = 'another-related-recipe';
        $this->ingredient->setRelation('recipes', [$recipe1, $recipe2]);

        $expectedResult = collect([$recipe1->slug, $recipe2->slug]);

        $this->ingredientRepository
            ->expects($this->once())
            ->method('get')
            ->with($this->ingredientId)
            ->willReturn($this->ingredient);

        $ingredientService = new IngredientService($this->ingredientRepository);

        $this->assertEquals($expectedResult, $ingredientService->relatedRecipes($this->ingredientId));
    }

    /** @test */
    public function returns_empty_collection_if_no_related_recipes_exist()
    {
        $this->ingredient->setRelation('recipes', []);

        $expectedResult = collect([]);

        $this->ingredientRepository
            ->expects($this->once())
            ->method('get')
            ->with($this->ingredientId)
            ->willReturn($this->ingredient);

        $ingredientService = new IngredientService($this->ingredientRepository);

        $this->assertEquals($expectedResult, $ingredientService->relatedRecipes($this->ingredientId));
    }
}
