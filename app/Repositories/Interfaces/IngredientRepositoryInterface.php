<?php

namespace App\Repositories\Interfaces;

use App\Models\Ingredient;
use Illuminate\Support\Collection;

interface IngredientRepositoryInterface
{
    public function get(int $id) : Ingredient;
    public function getAll() : Collection;
    public function getByName(string $name) : Ingredient;
    public function getByNameLimited(string $name, int $limit) : Collection;
    public function create(array $attributes) : Ingredient;
    public function update(int $id, array $attributes) : Ingredient;
    public function delete(int $id) : bool;
}
