<?php

namespace App\Services\Interfaces\ShoppingList;

use App\Repositories\Interfaces\ShoppingListRepositoryInterface;

interface DeleteShoppingListInterface
{
    public function __construct(
        ShoppingListRepositoryInterface $shoppingListRepository
    );
    public function setUser(int $userId): self;
    public function trash(int $shoppingListId) : bool;
    public function restore(int $shoppingListId) : bool;
    public function delete(int $shoppingListId) : bool;
}
