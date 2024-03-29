<?php

namespace App\Repositories;

use App\Models\Ingredient;
use App\Repositories\Interfaces\IngredientRepositoryInterface;
use Illuminate\Support\Collection;

class IngredientRepository implements IngredientRepositoryInterface
{
    public function get(int $id) : Ingredient
    {
        return Ingredient::find($id);
    }

    public function getAll(): Collection
    {
        return Ingredient::all();
    }

    public function create(array $attributes) : Ingredient
    {
        return Ingredient::create($attributes);
    }

    public function update(int $id, array $attributes) : Ingredient
    {
        Ingredient::where('id', $id)->update($attributes);

        return $this->get($id);
    }

    public function delete(int $id): bool
    {
        return Ingredient::destroy($id);
    }

    public function getByNameLimited(string $name, int $limit) : Collection
    {
        return Ingredient::select(
            'ingredients.id',
            'ingredients.name',
            'protein',
            'fat',
            'carbohydrate',
            'kcal',
            'ingredient_category_id',
            'units.symbol as unit',
            'units.id as unit_id'
        )
            ->join(
            'units',
            'units.id',
            'ingredients.unit_id'
        )
            ->where('ingredients.name', 'like', $name)
            ->limit($limit)
            ->get();
    }

    public function getByName(string $name): Ingredient
    {
        return Ingredient::where('name', '=', $name)
            ->firstOrFail();
    }
}
