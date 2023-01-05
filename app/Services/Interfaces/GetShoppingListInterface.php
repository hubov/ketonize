<?php

namespace App\Services\Interfaces;

use App\Repositories\Interfaces\ShoppingListRepositoryInterface;

interface GetShoppingListInterface
{
    public function __construct(ShoppingListRepositoryInterface $shoppingListRepository);
    public function retrieveForUser(int $userId) : array;
}
