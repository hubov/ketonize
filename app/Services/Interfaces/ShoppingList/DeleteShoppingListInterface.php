<?php

namespace App\Services\Interfaces\ShoppingList;

use App\Repositories\Interfaces\ShoppingListRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;

interface DeleteShoppingListInterface
{
    public function __construct(ShoppingListRepositoryInterface $shoppingListRepository, UserRepositoryInterface $userRepository);
    public function setUser(int $userId);
    public function delete(int $shoppingListId) : bool;
}
