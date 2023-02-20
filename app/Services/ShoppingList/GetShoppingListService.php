<?php

namespace App\Services\ShoppingList;

use App\Repositories\Interfaces\ShoppingListRepositoryInterface;
use App\Services\Interfaces\ShoppingList\GetShoppingListInterface;

class GetShoppingListService implements GetShoppingListInterface
{
    protected $shoppingListRepository;
    protected $shoppingList;
    protected $categorizedList = [];

    public function __construct(ShoppingListRepositoryInterface $shoppingListRepository)
    {
        $this->shoppingListRepository = $shoppingListRepository;

        return $this;
    }

    public function retrieveForUser (int $userId) : array
    {
        $this->shoppingList = $this->shoppingListRepository
            ->getByUser($userId)
            ->sortBy('name');

        $this->categorizeList();
        $this->sortListByCategory();

        return $this->categorizedList;
    }

    protected function categorizeList()
    {
        foreach ($this->shoppingList as $listElement) {
            $this->categorizedList[$listElement->ingredient->category->name][] = $listElement;
        }
    }

    protected function sortListByCategory()
    {
        ksort($this->categorizedList);
    }
}
