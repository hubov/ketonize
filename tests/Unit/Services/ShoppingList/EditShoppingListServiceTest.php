<?php

namespace Tests\Unit\Services\ShoppingList;

use App\Models\ShoppingList;
use App\Repositories\Interfaces\ShoppingListRepositoryInterface;
use App\Services\ShoppingList\EditShoppingListService;
use PHPUnit\Framework\TestCase;

class EditShoppingListServiceTest extends TestCase
{
    public $shoppingListId;
    public $shoppingList;
    public $userId;
    public $amount;
    public $shoppingListRepository;
    public $deleteShoppingListService;

    public function setUp(): void
    {
        $this->shoppingListId = 1;
        $this->shoppingList = new ShoppingList();
        $this->shoppingList->id = $this->shoppingListId;
        $this->userId = 1;
        $this->shoppingList->user_id = $this->userId;
        $this->amount = 100;

        $this->shoppingListRepository = $this->createMock(ShoppingListRepositoryInterface::class);

        $this->editShoppingListService = new EditShoppingListService($this->shoppingListRepository);

        $this->shoppingListRepository
            ->expects($this->once())
            ->method('get')
            ->with($this->shoppingListId)
            ->willReturn($this->shoppingList);
    }

    /** @test */
    public function returns_true_if_shopping_list_was_updated()
    {
        $this->shoppingListRepository
            ->expects($this->once())
            ->method('update')
            ->with($this->shoppingListId, ['amount' => $this->amount])
            ->willReturn(new ShoppingList());

        $this->assertTrue(
            $this->editShoppingListService
                ->setUser($this->userId)
                ->update($this->shoppingListId, $this->amount)
        );
    }

    /** @test */
    public function returns_false_if_shopping_list_did_not_exist()
    {
        $user2Id = 2;

        $this->shoppingListRepository
            ->expects($this->never())
            ->method('update');

        $this->assertFalse(
            $this->editShoppingListService
                ->setUser($user2Id)
                ->update($this->shoppingListId, $this->amount)
        );
    }
}
