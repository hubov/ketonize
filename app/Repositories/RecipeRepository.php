<?php

namespace App\Repositories;

use App\Models\Recipe;
use App\Repositories\Interfaces\RecipeRepositoryInterface;

class RecipeRepository implements RecipeRepositoryInterface
{
    public function get(int $id) : Recipe
    {
        return Recipe::find($id)->first();
    }

    public function getBySlug(string $slug): Recipe
    {
        return Recipe::where('slug', $slug)->firstOrFail();
    }
}
