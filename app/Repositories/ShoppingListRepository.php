<?php

namespace App\Repositories;

use App\Models\ShoppingList;
use App\Repositories\Interfaces\ShoppingListRepositoryInterface;
use Illuminate\Support\Collection;

class ShoppingListRepository implements ShoppingListRepositoryInterface
{
    public function get(int $id) : ShoppingList
    {
        return ShoppingList::find($id)->first();
    }

    public function getAll() : Collection
    {
        return ShoppingList::all();
    }

    public function getByUser(int $userId): Collection
    {
        return ShoppingList::where('user_id', $userId)->get();
    }

    public function create(array $attributes): ShoppingList
    {
        return ShoppingList::create($attributes);
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

    public function delete(int $id): bool
    {
        return ShoppingList::destroy($id);
    }
}
