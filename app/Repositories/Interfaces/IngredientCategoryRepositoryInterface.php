<?php

namespace App\Repositories\Interfaces;

use App\Models\IngredientCategory;
use Illuminate\Support\Collection;

interface IngredientCategoryRepositoryInterface
{
    public function get(int $id) : IngredientCategory;
    public function getAll() : Collection;
}
