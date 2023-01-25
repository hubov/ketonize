<?php

namespace App\Services\ShoppingList;

use App\Repositories\Interfaces\ShoppingListRepositoryInterface;
use App\Services\Interfaces\ShoppingList\DeleteShoppingListInterface;

class DeleteShoppingListService implements DeleteShoppingListInterface
{
    protected $shoppingListRepository;
    protected $userId;
    protected $shoppingList;

    public function __construct(
        ShoppingListRepositoryInterface $shoppingListRepository
    ) {
        $this->shoppingListRepository = $shoppingListRepository;

        return $this;
    }

    public function setUser(int $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    public function delete(int $shoppingListId): bool
    {
        $this->shoppingList = $this->shoppingListRepository->get($shoppingListId);

        if ($this->shoppingListExistsForUser())
        {
            $this->shoppingListRepository->delete($shoppingListId);
        } else {
            return false;
        }

        return true;
    }

    protected function shoppingListExistsForUser()
    {
        if (
            ($this->shoppingList) &&
            ($this->shoppingList->user->id == $this->userId)
        ) {
            return true;
        }

        return false;
    }
}
