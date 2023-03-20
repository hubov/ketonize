<?php

namespace App\Services\ShoppingList;

use App\Events\ShoppingList\ItemRestored;
use App\Events\ShoppingList\ItemTrashed;
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

    public function trash(int $shoppingListId): bool
    {
        if ($this->shoppingListExistsForUser($shoppingListId))
        {
            $this->shoppingListRepository->trash($shoppingListId);

            ItemTrashed::dispatch($this->shoppingList);
        } else {
            return false;
        }

        return true;
    }

    public function restore(int $shoppingListId): bool
    {
        if ($this->shoppingListExistsForUser($shoppingListId))
        {
            ItemRestored::dispatch($this->shoppingList);

            return $this->shoppingListRepository->restore($shoppingListId);
        } else {
            return false;
        }
    }

    public function delete(int $shoppingListId): bool
    {
        if ($this->shoppingListExistsForUser($shoppingListId))
        {
            $this->shoppingListRepository->delete($shoppingListId);
        } else {
            return false;
        }

        return true;
    }

    protected function shoppingListExistsForUser(int $shoppingListId)
    {
        $this->shoppingList = $this->shoppingListRepository->get($shoppingListId);

        if (
            ($this->shoppingList) &&
            ($this->shoppingList->user_id == $this->userId)
        ) {
            return true;
        }

        return false;
    }
}
