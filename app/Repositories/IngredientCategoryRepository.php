<?php

namespace App\Repositories;

use App\Models\IngredientCategory;
use App\Repositories\Interfaces\IngredientCategoryRepositoryInterface;
use Illuminate\Support\Collection;

class IngredientCategoryRepository implements IngredientCategoryRepositoryInterface
{
    public function get(int $id) : IngredientCategory
    {
        return IngredientCategory::find($id);
    }

    public function getAll(): Collection
    {
        return IngredientCategory::all();
    }
}
