<?php

namespace App\Services\ShoppingList;

use App\Repositories\Interfaces\ShoppingListRepositoryInterface;
use App\Services\Interfaces\ShoppingList\GetShoppingListInterface;

class GetShoppingListService implements GetShoppingListInterface
{
    protected $shoppingListRepository;
    protected $shoppingList;
    protected $categorizedList = [];
    protected $trashedList = [];

    public function __construct(ShoppingListRepositoryInterface $shoppingListRepository)
    {
        $this->shoppingListRepository = $shoppingListRepository;

        return $this;
    }

    public function retrieveForUser (int $userId) : array
    {
        $this->shoppingList = $this->shoppingListRepository
            ->getByUser($userId)
            ->sortBy('itemable.name');

        $this->categorizeList();
        $this->sortTrashedByTimestamp();

        return $this->categorizedList;
    }

    public function getTrashed()
    {
        return $this->trashedList;
    }

    protected function categorizeList()
    {
        foreach ($this->shoppingList as $listElement) {
            if ($listElement->trashed()) {
                $this->trashedList[$listElement->deleted_at->timestamp][] = $listElement;
            } else {
                $this->categorizedList[$listElement->itemable->ingredient_category_id][] = $listElement;
            }
        }
    }

    protected function sortTrashedByTimestamp()
    {
        krsort($this->trashedList);
    }
}
