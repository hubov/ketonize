<?php

namespace Tests\Unit\Services;

use App\Models\IngredientCategory;
use App\Repositories\Interfaces\IngredientCategoryRepositoryInterface;
use App\Services\IngredientCategoryService;
use PHPUnit\Framework\TestCase;

class IngredientCategoryServiceTest extends TestCase
{
    protected $ingredientCategoryRepository;

    protected function setUp(): void
    {
        $this->ingredientCategoryRepository = $this->createMock(IngredientCategoryRepositoryInterface::class);
    }

    /** @test */
    public function returns_empty_array_if_no_ingredients_categories_exist()
    {
        $this->ingredientCategoryRepository
            ->expects($this->once())
            ->method('getAll')
            ->willReturn(collect([]));

        $ingredientCategoryService = new IngredientCategoryService($this->ingredientCategoryRepository);

        $this->assertEquals(
            [],
            $ingredientCategoryService
                ->getAllByName()
        );
    }

    /** @test */
    public function returns_list_with_names_as_keys()
    {
        $category = new IngredientCategory();
        $category->id = 1;
        $category->name = 'Category 1';

        $this->ingredientCategoryRepository
            ->expects($this->once())
            ->method('getAll')
            ->willReturn(collect([$category]));

        $ingredientCategoryService = new IngredientCategoryService($this->ingredientCategoryRepository);

        $this->assertEquals(
            [
                $category->name => $category
            ],
            $ingredientCategoryService
                ->getAllByName()
        );
    }
}
