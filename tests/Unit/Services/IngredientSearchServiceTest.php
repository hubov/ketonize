<?php

namespace Tests\Unit\Services;

use App\Models\Ingredient;
use App\Repositories\Interfaces\IngredientRepositoryInterface;
use App\Services\IngredientSearchService;
use PHPUnit\Framework\TestCase;

class IngredientSearchServiceTest extends TestCase
{
    public $ingredientRepository;

    public function setUp(): void
    {
        parent::setUp();

        $this->ingredientRepository = $this->createMock(IngredientRepositoryInterface::class);
    }

    /** @test */
    public function returns_search_results_if_similar_records_exist()
    {
        $ingredient1 = new Ingredient(['name' => 'Dried tomato in olive']);
        $ingredient2 = new Ingredient(['name' => 'Dried tomato']);
        $ingredient3 = new Ingredient(['name' => 'Tomato']);

        $ingredientsList = collect([
            $ingredient1,
            $ingredient2,
            $ingredient3,
        ]);
        $query = 'tomat';
        $limit = 3;

        $this->ingredientRepository
            ->expects($this->once())
            ->method('getByNameLimited')
            ->with('%'.$query.'%', $limit)
            ->willReturn($ingredientsList);

        $ingredientSearchService = new IngredientSearchService($this->ingredientRepository);
        $ingredientSearchService
            ->limit($limit)
            ->query($query);

        foreach ($ingredientSearchService->return() as $test) {
            $resultsOrder[] = $test;
        }

        $this->assertEquals(
            [$ingredient3, $ingredient2, $ingredient1],
            $resultsOrder
        );
    }

    /** @test */
    public function returns_empty_array_if_no_similar_records_exist()
    {
        $ingredientsList = collect([]);
        $query = 'pome';
        $limit = 3;

        $this->ingredientRepository
            ->expects($this->once())
            ->method('getByNameLimited')
            ->with('%'.$query.'%', $limit)
            ->willReturn($ingredientsList);

        $ingredientSearchService = new IngredientSearchService($this->ingredientRepository);

        $this->assertEquals(
            [],
            $ingredientSearchService
                ->limit($limit)
                ->query($query)
                ->return()
        );
    }
}
