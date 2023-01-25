<?php

namespace App\Services\Interfaces\ShoppingList;

use App\Repositories\Interfaces\ShoppingListRepositoryInterface;

interface EditShoppingListInterface
{
    public function __construct(
        ShoppingListRepositoryInterface $shoppingListRepository,
    );
    public function setUser(int $userId): self;
    public function update(int $shoppingListId, int $amount) : bool;
}
