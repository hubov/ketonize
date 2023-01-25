<?php

namespace App\Services\Interfaces\ShoppingList;

use App\Repositories\Interfaces\ShoppingListRepositoryInterface;
use App\Services\Interfaces\MealInterface;

interface UpdateShoppingListInterface
{
    public function __construct(
        ShoppingListRepositoryInterface $shoppingListRepository,
        MealInterface $mealService,
    );
    public function setUser(int $userId): self;
    public function setDates(string $dateFrom, string $dateTo): self;
    public function update(): void;
}
