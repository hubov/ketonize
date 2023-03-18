<?php

namespace App\Repositories;

use App\Models\CustomIngredient;
use App\Models\Interfaces\IngredientModelInterface;
use App\Models\ShoppingList;
use App\Repositories\Interfaces\ShoppingListRepositoryInterface;
use Illuminate\Support\Collection;

class ShoppingListRepository implements ShoppingListRepositoryInterface
{
    public function get(int $id) : ?ShoppingList
    {
        return ShoppingList::withTrashed()->with('itemable')->find($id);
    }

    public function getAll() : Collection
    {
        return ShoppingList::all();
    }

    public function getByUser(int $userId): Collection
    {
        return ShoppingList::withTrashed()
            ->with('itemable')
            ->with('itemable.unit')
            ->where('user_id', $userId)
            ->get();
    }

    public function getByIngredientUser(IngredientModelInterface $ingredient, int $userId) : ShoppingList
    {
        return ShoppingList::withTrashed()
            ->where('user_id', $userId)
            ->where('itemable_id', $ingredient->id)
            ->where('itemable_type', get_class($ingredient))
            ->firstOrFail();
    }

    public function create(array $attributes): ShoppingList
    {
        return ShoppingList::create($attributes);
    }

    public function createForUser(int $userId, array $attributes)
    {
        $attributes['user_id'] = $userId;

        $shoppingList = ShoppingList::create($attributes);
        $result = ShoppingList::with(['itemable'])->find($shoppingList->id);

        return $result;
    }

    public function createForUserBulk(int $userId, array $attributes)
    {
        foreach ($attributes as $key => $row) {
            $attributes[$key]['user_id'] = $userId;
        }

        return ShoppingList::insert($attributes);
    }

    public function update(int $id, array $attributes): ShoppingList
    {
        $shoppingList = ShoppingList::find($id);
        $shoppingList->update($attributes);

        return $shoppingList;
    }

    public function updateByUser(int $userId, array $attributes): ShoppingList
    {
        $shoppingList = ShoppingList::where('user_id', $userId)->first();
        $shoppingList->update($attributes);

        return $shoppingList;
    }

    public function increase(int $shopppingListId, int $amount): ShoppingList
    {
        $shoppingList = ShoppingList::find($shopppingListId);
        $shoppingList->increment('amount', $amount);

        return $shoppingList;
    }

    public function trash(int $shoppingListId): bool
    {
        return ShoppingList::destroy($shoppingListId);
    }

    public function restore(int $shoppingListId): bool
    {
        ShoppingList::withTrashed()
            ->find($shoppingListId)
            ->restore();

        return true;
    }

    public function delete(int $shoppingListId): bool
    {
        return ShoppingList::withTrashed()
            ->find($shoppingListId)
            ->forceDelete();
    }

    public function deleteForUser(int $userId): bool
    {
        return ShoppingList::withTrashed()
            ->where('user_id', $userId)
            ->forceDelete();
    }
}
