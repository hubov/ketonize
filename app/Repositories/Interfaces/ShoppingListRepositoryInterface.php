<?php

namespace App\Repositories\Interfaces;

use App\Models\ShoppingList;
use Illuminate\Support\Collection;

interface ShoppingListRepositoryInterface
{
    public function get(int $id) : ShoppingList;
    public function getByUser(int $userId) : Collection;
    public function create(array $attributes) : ShoppingList;
    public function update(int $id, array $attributes) : ShoppingList;
    public function updateByUser(int $userId, array $attributes) : ShoppingList;
    public function delete(int $id) : bool;
}
