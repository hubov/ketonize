<?php

namespace App\Repositories;

use App\Models\ShoppingList;
use App\Repositories\Interfaces\ShoppingListRepositoryInterface;
use Illuminate\Support\Collection;

class ShoppingListRepository implements ShoppingListRepositoryInterface
{
    public function get(int $id) : ?ShoppingList
    {
        return ShoppingList::withTrashed()->find($id);
    }

    public function getAll() : Collection
    {
        return ShoppingList::all();
    }

    public function getByUser(int $userId): Collection
    {
        return ShoppingList::withTrashed()
            ->with('ingredient')
            ->with('ingredient.category')
            ->with('ingredient.unit')
            ->where('user_id', $userId)
            ->get();
    }

    public function getByIngredientUser(int $ingredientId, int $userId) : ShoppingList
    {
        return ShoppingList::withTrashed()
            ->where('user_id', $userId)
            ->where('ingredient_id', $ingredientId)
            ->firstOrFail();
    }

    public function create(array $attributes): ShoppingList
    {
        return ShoppingList::create($attributes);
    }

    public function createForUser(int $userId, array $attributes)
    {
        $attributes['user_id'] = $userId;

        return ShoppingList::create($attributes);
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
