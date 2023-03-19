<?php

namespace Tests\Unit\Services\ShoppingList;

use App\Models\ShoppingList;
use App\Repositories\Interfaces\ShoppingListRepositoryInterface;
use App\Services\ShoppingList\DeleteShoppingListService;
use Tests\TestCase;

class DeleteShoppingListServiceTest extends TestCase
{
    public $shoppingListId;
    public $shoppingList;
    public $userId;
    public $shoppingListRepository;
    public $deleteShoppingListService;

    public function setUp(): void
    {
        parent::setUp();

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

    /** @test */
    public function returns_true_if_item_was_trashed()
    {
        $this->withoutEvents();

        $this->shoppingListRepository
            ->expects($this->once())
            ->method('trash')
            ->with($this->shoppingListId)
            ->willReturn(true);

        $this->assertTrue(
            $this->deleteShoppingListService
                ->setUser($this->userId)
                ->trash($this->shoppingListId)
        );
    }

    /** @test */
    public function returns_false_if_item_was_already_trashed()
    {
        $user2Id = 2;

        $this->shoppingListRepository
            ->expects($this->never())
            ->method('trash');

        $this->assertFalse(
            $this->deleteShoppingListService
                ->setUser($user2Id)
                ->trash($this->shoppingListId)
        );
    }

    /** @test */
    public function returns_true_if_item_successfully_restored()
    {
        $this->withoutEvents();

        $this->shoppingListRepository
            ->expects($this->once())
            ->method('restore')
            ->with($this->shoppingListId)
            ->willReturn(true);

        $this->assertTrue(
            $this->deleteShoppingListService
                ->setUser($this->userId)
                ->restore($this->shoppingListId)
        );
    }

    /** @test */
    public function returns_false_if_item_successfully_restored()
    {
        $user2Id = 2;

        $this->shoppingListRepository
            ->expects($this->never())
            ->method('restore');

        $this->assertFalse(
            $this->deleteShoppingListService
                ->setUser($user2Id)
                ->restore($this->shoppingListId)
        );
    }
}
