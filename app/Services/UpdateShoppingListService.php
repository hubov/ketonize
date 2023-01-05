<?php

namespace App\Services;

use App\Repositories\Interfaces\MealRepositoryInterface;
use App\Repositories\Interfaces\ShoppingListRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Services\Interfaces\UpdateShoppingListInterface;

class UpdateShoppingListService implements UpdateShoppingListInterface
{
    protected $shoppingListRepository;
    protected $mealRepository;
    protected $userRepository;
    protected $user;
    protected $dateFrom;
    protected $dateTo;
    protected $meals;
    protected $listItems = [];

    public function __construct(ShoppingListRepositoryInterface $shoppingListRepository, MealRepositoryInterface $mealRepository, UserRepositoryInterface $userRepository)
    {
        $this->shoppingListRepository = $shoppingListRepository;
        $this->mealRepository = $mealRepository;
        $this->userRepository = $userRepository;

        return $this;
    }

    public function setUser(int $userId)
    {
        $this->user = $this->userRepository->get($userId);

        return $this;
    }

    public function setDates(string $dateFrom, string $dateTo)
    {
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;

        return $this;
    }

    public function update()
    {
        $this->meals = $this->mealRepository->getForUserBetweenDates($this->user->id, $this->dateFrom, $this->dateTo);

        $this->getIngredientsFromMeals();
        $this->shoppingListRepository->deleteForUser($this->user->id);
        $this->shoppingListRepository->createForUserBulk($this->user->id, $this->listItems);
    }

    protected function getIngredientsFromMeals()
    {
        foreach ($this->meals as $meal) {
            foreach ($meal->recipe->ingredients as $ingredient) {
                if (isset($this->listItems[$ingredient->id])) {
                    $this->listItems[$ingredient->id]['amount'] += round($ingredient->pivot->amount * $meal->modifier / 100);
                } else {
                    $this->listItems[$ingredient->id] = [
                        'ingredient_id' => $ingredient->id,
                        'amount' => round($ingredient->pivot->amount * $meal->modifier / 100)
                    ];
                }
            }
        }
    }
}
