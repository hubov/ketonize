<?php

namespace Tests\Unit\Services\ShoppingList;

use App\Models\ShoppingList;
use App\Repositories\Interfaces\ShoppingListRepositoryInterface;
use App\Services\ShoppingList\DeleteShoppingListService;
use PHPUnit\Framework\TestCase;

class DeleteShoppingListServiceTest extends TestCase
{
    public $shoppingListId;
    public $shoppingList;
    public $userId;
    public $shoppingListRepository;
    public $deleteShoppingListService;

    public function setUp(): void
    {
        $this->shoppingListId = 1;
        $this->shoppingList = new ShoppingList();
        $this->shoppingList->id = $this->shoppingListId;
        $this->userId = 1;
        $this->shoppingList->user_id = $this->userId;

        $this->shoppingListRepository = $this->createMock(ShoppingListRepositoryInterface::class);

        $this->deleteShoppingListService = new DeleteShoppingListService($this->shoppingListRepository);

        $this->shoppingListRepository
            ->expects($this->once())
            ->method('get')
            ->with($this->shoppingListId)
            ->willReturn($this->shoppingList);
    }

    /** @test */
    public function returns_true_if_shopping_list_was_removed()
    {
        $this->shoppingListRepository
            ->expects($this->once())
            ->method('delete')
            ->with($this->shoppingListId)
            ->willReturn(true);

        $this->assertTrue(
            $this->deleteShoppingListService
                ->setUser($this->userId)
                ->delete($this->shoppingListId)
        );
    }

    /** @test */
    public function returns_false_if_shopping_list_did_not_exist()
    {
        $user2Id = 2;

        $this->shoppingListRepository
            ->expects($this->never())
            ->method('delete');

        $this->assertFalse(
            $this->deleteShoppingListService
                ->setUser($user2Id)
                ->delete($this->shoppingListId)
        );
    }
}