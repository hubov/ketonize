<?php

namespace App\Services\Interfaces\ShoppingList;

use App\Repositories\Interfaces\ShoppingListRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;

interface EditShoppingListInterface
{
    public function __construct(
        ShoppingListRepositoryInterface $shoppingListRepository,
        UserRepositoryInterface $userRepository
    );
    public function setUser(int $userId): self;
    public function update(int $shoppingListId, int $amount) : bool;
}
