<?php

namespace App\Repositories;

use App\Models\Recipe;
use App\Repositories\Interfaces\RecipeRepositoryInterface;
use Illuminate\Support\Collection;

class RecipeRepository implements RecipeRepositoryInterface
{
    public function get(int $id) : Recipe
    {
        return Recipe::find($id)->first();
    }

    public function getAll() : Collection
    {
        return Recipe::all();
    }

    public function getBySlug(string $slug): Recipe
    {
        return Recipe::where('slug', $slug)->firstOrFail();
    }

    public function getOneByAttributesAndTagsWithoutIds(array $attributes, array $tags, array $excludedIds): Recipe
    {
        $recipe = Recipe::join('recipe_tag', 'recipes.id', '=', 'recipe_id')
            ->select('recipes.*')
            ->whereNotIn('recipes.id', $excludedIds)
            ->whereIn('tag_id', $tags);

        foreach ($attributes as $attributeName => $value) {
            if (is_array($value)) {
                $recipe->whereBetween($attributeName, $value);
            } else {
                $recipe->where($attributeName, $value);
            }
        }

        return $recipe->inRandomOrder()
                        ->first();
    }

    public function create(array $attributes): Recipe
    {
        return Recipe::create($attributes);
    }

    public function update(int $id, array $attributes): Recipe
    {
        $recipe = Recipe::find($id);
        $recipe->update($attributes);

        return $recipe;
    }

    public function updateBySlug(string $slug, array $attributes): Recipe
    {
        $recipe = Recipe::where('slug', $slug)->first();
        $recipe->update($attributes);

        return $recipe;
    }

    public function delete(int $id): bool
    {
        return Recipe::destroy($id);
    }
}
