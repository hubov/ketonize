<?php

namespace App\Services\Interfaces\ShoppingList;

use App\Repositories\Interfaces\MealRepositoryInterface;
use App\Repositories\Interfaces\ShoppingListRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;

interface UpdateShoppingListInterface
{
    public function __construct(
        ShoppingListRepositoryInterface $shoppingListRepository,
        MealRepositoryInterface $mealRepository,
        UserRepositoryInterface $userRepository
    );
    public function setUser(int $userId): self;
    public function setDates(string $dateFrom, string $dateTo): self;
    public function update(): void;
}
