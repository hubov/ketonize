<?php

namespace App\Services\ShoppingList;

use App\Repositories\Interfaces\ShoppingListRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Services\Interfaces\ShoppingList\EditShoppingListInterface;

class EditShoppingListService implements EditShoppingListInterface
{
    protected $shoppingListRepository;
    protected $userRepository;
    protected $user;
    protected $shoppingList;

    public function __construct(ShoppingListRepositoryInterface $shoppingListRepository, UserRepositoryInterface $userRepository)
    {
        $this->shoppingListRepository = $shoppingListRepository;
        $this->userRepository = $userRepository;

        return $this;
    }

    public function setUser(int $userId)
    {
        $this->user = $this->userRepository->get($userId);

        return $this;
    }

    public function update(int $shoppingListId, int $amount) : bool
    {
        $this->shoppingList = $this->shoppingListRepository->get($shoppingListId);

        if ($this->shoppingListExistsForUser()) {
            $this->updateShoppingListAmount($amount);
        } else {
            return false;
        }

        return true;
    }

    protected function shoppingListExistsForUser()
    {
        if (($this->shoppingList) && ($this->shoppingList->user->id == $this->user->id)) {
            return true;
        } else {
            return false;
        }
    }

    protected function updateShoppingListAmount($amount)
    {
        $this->shoppingList->amount = $amount;
        $this->shoppingList->save();
    }
}
