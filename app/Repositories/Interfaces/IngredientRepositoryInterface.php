<?php

namespace App\Repositories\Interfaces;

use App\Models\Ingredient;
use Illuminate\Support\Collection;

interface IngredientRepositoryInterface
{
    public function get(int $id) : Ingredient;
    public function getAll() : Collection;
    public function create(array $attributes) : Ingredient;
    public function update(int $id, array $attributes) : Ingredient;
    public function delete(int $id) : bool;
}
