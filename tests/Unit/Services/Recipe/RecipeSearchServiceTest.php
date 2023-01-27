<?php

namespace Tests\Unit\Services\Recipe;

use App\Models\Recipe;
use App\Repositories\Interfaces\RecipeSearchRepositoryInterface;
use App\Services\Recipe\RecipeSearchService;
use Tests\TestCase;


class RecipeSearchServiceTest extends TestCase
{
    public $recipeSearchRepository;
    public $recipeSearchService;

    public function setUp(): void
    {
        $this->recipeSearchRepository = $this->createMock(RecipeSearchRepositoryInterface::class);

        $this->recipeSearchService = new RecipeSearchService($this->recipeSearchRepository);
    }

    /** @test */
    public function search_by_query_recipe_name()
    {
        $recipe = new Recipe();
        $recipe->name = 'aaa';

        $this->filterByQuery();

        $this->recipeSearchRepository
            ->expects($this->once())
            ->method('get')
            ->willReturn(collect([$recipe]));

        $result = $this->recipeSearchService
            ->filters(['query' => 'a'])
            ->search();

        $this->assertCount(1, $result);
    }

    /** @test */
    public function search_by_query_ingredient_name()
    {
        $recipe = new Recipe();
        $recipe->name = 'ccc';

        $this->filterByQuery();

        $this->recipeSearchRepository
            ->expects($this->once())
            ->method('get')
            ->willReturn(collect([$recipe]));

        $result = $this->recipeSearchService
            ->filters(['query' => 'a'])
            ->search();

        $this->assertCount(1, $result);
    }

    protected function filterByQuery()
    {
        $this->recipeSearchRepository
            ->expects($this->once())
            ->method('filterByRecipeName')
            ->with('a')
            ->willReturnSelf();
        $this->recipeSearchRepository
            ->expects($this->once())
            ->method('filterByIngredientName')
            ->with('a')
            ->willReturnSelf();
    }

    /** @test */
    public function search_by_tag()
    {
        $recipe = new Recipe();
        $recipe->name = 'aaa';

        $this->recipeSearchRepository
            ->expects($this->once())
            ->method('filterByTags')
            ->with([1])
            ->willReturnSelf();

        $this->recipeSearchRepository
            ->expects($this->once())
            ->method('get')
            ->willReturn(collect([$recipe]));

        $result = $this->recipeSearchService
            ->filters(['tags' => [1]])
            ->search();

        $this->assertCount(1, $result);
    }
}
