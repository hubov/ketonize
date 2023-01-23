<?php

namespace App\Services\ShoppingList;

use App\Repositories\Interfaces\ShoppingListRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Services\Interfaces\ShoppingList\DeleteShoppingListInterface;

class DeleteShoppingListService implements DeleteShoppingListInterface
{
    protected $shoppingListRepository;
    protected $userRepository;
    protected $shoppingList;

    public function __construct(
        ShoppingListRepositoryInterface $shoppingListRepository,
        UserRepositoryInterface $userRepository
    ) {
        $this->shoppingListRepository = $shoppingListRepository;
        $this->userRepository = $userRepository;

        return $this;
    }

    public function setUser(int $userId): self
    {
        $this->user = $this->userRepository->get($userId);

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
            ($this->shoppingList->user->id == $this->user->id)
        ) {
            return true;
        }

        return false;
    }
}
