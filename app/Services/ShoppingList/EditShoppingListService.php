<?php

namespace App\Services\ShoppingList;

use App\Repositories\Interfaces\ShoppingListRepositoryInterface;
use App\Services\Interfaces\ShoppingList\EditShoppingListInterface;

class EditShoppingListService implements EditShoppingListInterface
{
    protected $shoppingListRepository;
    protected $userId;
    protected $shoppingList;

    public function __construct(
        ShoppingListRepositoryInterface $shoppingListRepository,
    ) {
        $this->shoppingListRepository = $shoppingListRepository;

        return $this;
    }

    public function setUser(int $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    public function update(int $shoppingListId, int $amount) : bool
    {
        $this->shoppingList = $this->shoppingListRepository->get($shoppingListId);

        if ($this->shoppingListExistsForUser()) {
            $this->shoppingListRepository->update($this->shoppingList->id, ['amount' => $amount]);
        } else {
            return false;
        }

        return true;
    }

    protected function shoppingListExistsForUser()
    {
        if (($this->shoppingList) && ($this->shoppingList->user_id == $this->userId)) {
            return true;
        } else {
            return false;
        }
    }
}
