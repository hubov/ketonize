<?php

namespace App\Services\ShoppingList;

use App\Models\Ingredient;
use App\Models\Interfaces\IngredientModelInterface;
use App\Repositories\CustomIngredientRepository;
use App\Repositories\Interfaces\CustomIngredientRepositoryInterface;
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
    protected $customIngredientRepository;
    protected $userId;
    protected $dateFrom;
    protected $dateTo;
    protected $meals;
    protected $listItems = [];

    public function __construct(
        ShoppingListRepositoryInterface $shoppingListRepository,
        MealInterface $mealService,
        IngredientRepositoryInterface $ingredientRepository,
        CustomIngredientRepositoryInterface $customIngredientRepository
    ) {
        $this->shoppingListRepository = $shoppingListRepository;
        $this->mealService = $mealService;
        $this->ingredientRepository = $ingredientRepository;
        $this->customIngredientRepository = $customIngredientRepository;

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
        } catch (ModelNotFoundException) {
            $ingredient = $this->customIngredientRepository->getOrCreateForUserByName($this->userId, $attributes);
        }

        $this->addIngredient($ingredient, $attributes);

        return true;
    }

    protected function addIngredient(IngredientModelInterface $ingredient, array $attributes): void
    {
        try {
            $existingShoppingList = $this->shoppingListRepository->getByIngredientUser($ingredient, $this->userId);

            if ($existingShoppingList->trashed()) {
                $this->shoppingListRepository->restore($existingShoppingList->id);
                $this->shoppingListRepository->update($existingShoppingList->id, ['amount' => $attributes['amount']]);
            } else {
                $this->shoppingListRepository->increase($existingShoppingList->id, $attributes['amount']);
            }
        } catch (ModelNotFoundException) {
            $this->createShoppingListItem($ingredient, $attributes['amount']);
        }
    }

    protected function createShoppingListItem(IngredientModelInterface $ingredient, int $amount)
    {
        $this->shoppingListRepository
            ->createForUser(
                $this->userId,
                [
                    'itemable_id' => $ingredient->id,
                    'itemable_type' => get_class($ingredient),
                    'amount' => $amount
                ]
            );
    }
}
