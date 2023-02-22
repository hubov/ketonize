<?php

namespace App\Services\ShoppingList;

use App\Models\Ingredient;
use App\Repositories\Interfaces\IngredientRepositoryInterface;
use App\Repositories\Interfaces\ShoppingListRepositoryInterface;
use App\Services\Interfaces\MealInterface;
use App\Services\Interfaces\ShoppingList\UpdateShoppingListInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UpdateShoppingListService implements UpdateShoppingListInterface
{
    protected $shoppingListRepository;
    protected $mealService;
    protected $ingredientRepository;
    protected $userId;
    protected $dateFrom;
    protected $dateTo;
    protected $meals;
    protected $listItems = [];

    public function __construct(
        ShoppingListRepositoryInterface $shoppingListRepository,
        MealInterface $mealService,
        IngredientRepositoryInterface $ingredientRepository
    ) {
        $this->shoppingListRepository = $shoppingListRepository;
        $this->mealService = $mealService;
        $this->ingredientRepository = $ingredientRepository;

        return $this;
    }

    public function setUser(int $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    public function setDates(string $dateFrom, string $dateTo): self
    {
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;

        return $this;
    }

    public function update(): void
    {
        $this->listItems = $this->mealService->getIngredientsBetweenDates($this->userId, $this->dateFrom, $this->dateTo);

        $this->shoppingListRepository->deleteForUser($this->userId);
        $this->shoppingListRepository->createForUserBulk($this->userId, $this->listItems);
    }

    public function add(array $attributes): bool
    {
        try {
            $ingredient = $this->ingredientRepository->getByName($attributes['item_name']);

            $this->addExistingIngredient($ingredient, $attributes);
        } catch (ModelNotFoundException) {
            dd('non existing ingredient');
        }

        return true;
    }

    protected function addExistingIngredient(Ingredient $ingredient, array $attributes): void
    {
        try {
            $existingShoppingList = $this->shoppingListRepository->getByIngredientUser($ingredient->id, $this->userId);

            if ($existingShoppingList->trashed()) {
                $this->shoppingListRepository->restore($existingShoppingList->id);
                $this->shoppingListRepository->update($existingShoppingList->id, ['amount' => $attributes['amount']]);
            }
        } catch (ModelNotFoundException) {
            $this->createShoppingListItem($ingredient->id, $attributes['amount']);
        }
    }

    protected function createShoppingListItem(int $ingredientId, int $amount)
    {
        $this->shoppingListRepository
            ->createForUser(
                $this->userId,
                [
                    'ingredient_id' => $ingredientId,
                    'amount' => $amount
                ]
            );
    }
}
