<?php

namespace Tests\Unit\Services\ShoppingList;

use App\Models\Ingredient;
use App\Models\IngredientCategory;
use App\Models\ShoppingList;
use App\Repositories\Interfaces\ShoppingListRepositoryInterface;
use App\Services\ShoppingList\GetShoppingListService;
use PHPUnit\Framework\TestCase;

class GetShoppingListServiceTest extends TestCase
{
    public $userId;
    public $shoppingListElement;
    public $shoppingList;
    public $shoppingListRepository;
    public $getShoppingListService;

    public function setUp(): void
    {
        $this->userId = 1;
        $this->categoryName = 'Some category';
        $this->shoppingListElement = new ShoppingList();
        $this->shoppingListElement->itemable = new Ingredient();
        $this->shoppingListElement->itemable->ingredient_category_id = 1;
        $this->shoppingListElement->itemable->category = new IngredientCategory();
        $this->shoppingListElement->itemable->category->name = $this->categoryName;
        $this->shoppingList = collect([$this->shoppingListElement]);

        $this->shoppingListRepository = $this->createMock(ShoppingListRepositoryInterface::class);

        $this->getShoppingListService = new GetShoppingListService($this->shoppingListRepository);
    }

    /** @test */
    public function it_returns_shopping_list_if_one_exists()
    {
        $this->shoppingListRepository
            ->expects($this->once())
            ->method('getByUser')
            ->with($this->userId)
            ->willReturn($this->shoppingList);

        $this->assertEquals(
            [
                $this->shoppingListElement->itemable->ingredient_category_id => [
                    $this->shoppingListElement
                ]
            ],
            $this->getShoppingListService
                ->retrieveForUser($this->userId)
        );
    }

    /** @test */
    public function it_returns_empty_array_if_none_exists()
    {
        $this->shoppingListRepository
            ->expects($this->once())
            ->method('getByUser')
            ->with($this->userId)
            ->willReturn(collect([]));

        $this->assertEquals(
            [],
            $this->getShoppingListService
                ->retrieveForUser($this->userId)
        );
    }
}
