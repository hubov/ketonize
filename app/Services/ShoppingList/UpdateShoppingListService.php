<?php

namespace App\Services\ShoppingList;

use App\Events\ShoppingList\ItemAdded;
use App\Events\ShoppingList\ItemUpdated;
use App\Http\Traits\UniversalIngredientPicker;
use App\Models\Interfaces\IngredientModelInterface;
use App\Models\ShoppingList;
use App\Repositories\Interfaces\CustomIngredientRepositoryInterface;
use App\Repositories\Interfaces\IngredientRepositoryInterface;
use App\Repositories\Interfaces\ShoppingListRepositoryInterface;
use App\Services\Interfaces\MealInterface;
use App\Services\Interfaces\ShoppingList\UpdateShoppingListInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UpdateShoppingListService implements UpdateShoppingListInterface
{
    use UniversalIngredientPicker;

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

    public function add(array $attributes): ShoppingList
    {
        return $this->addIngredient(
                $this->getOrCreateIngredientByName($attributes),
                $attributes
            );
    }

    protected function addIngredient(IngredientModelInterface $ingredient, array $attributes): ShoppingList
    {
        try {
            $shoppingList = $this->shoppingListRepository->getByIngredientUser($ingredient, $this->userId);

            if ($shoppingList->trashed()) {
                $this->shoppingListRepository->restore($shoppingList->id);
                $this->shoppingListRepository->update($shoppingList->id, ['amount' => $attributes['amount']]);
            } else {
                $this->shoppingListRepository->increase($shoppingList->id, $attributes['amount']);
            }

            ItemUpdated::dispatch($shoppingList);
        } catch (ModelNotFoundException) {
            $shoppingList = $this->createShoppingListItem($ingredient, $attributes['amount']);

            ItemAdded::dispatch($shoppingList);
        }

        return $shoppingList;
    }

    protected function createShoppingListItem(IngredientModelInterface $ingredient, int $amount): ShoppingList
    {
        $shoppingList = $this->shoppingListRepository
            ->createForUser(
                $this->userId,
                [
                    'itemable_id' => $ingredient->id,
                    'itemable_type' => get_class($ingredient),
                    'amount' => $amount
                ]
            );

        return $shoppingList;
    }
}
