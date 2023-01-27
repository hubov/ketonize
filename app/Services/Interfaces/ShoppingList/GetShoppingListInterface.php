<?php

namespace App\Services\Interfaces\ShoppingList;

use App\Repositories\Interfaces\ShoppingListRepositoryInterface;

interface GetShoppingListInterface
{
    public function __construct(ShoppingListRepositoryInterface $shoppingListRepository);
    public function retrieveForUser(int $userId) : array;
}
