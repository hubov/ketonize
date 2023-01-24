<?php

namespace App\Repositories;

use App\Models\Ingredient;
use App\Models\Recipe;
use App\Repositories\Interfaces\RecipeSearchRepositoryInterface;
use Illuminate\Support\Collection;

class RecipeSearchRepository implements RecipeSearchRepositoryInterface
{
    protected $recipe;
    protected $ingredient;

    public function __construct(
        Recipe $recipe,
        Ingredient $ingredient
    ) {
        $this->recipe = $recipe;
        $this->ingredient = $ingredient;

        $this->recipe->select('name', 'slug', 'image', 'preparation_time', 'total_time', 'protein_ratio', 'fat_ratio', 'carbohydrate_ratio');
    }

    public function filterByTags(array $tags): self
    {
        $this->recipe->whereRelation('tags', function ($query) use ($tags) {
            return $query->whereIn('tags.id', $tags);
        });

        return $this;
    }

    public function filterByRecipeName(string $query): self
    {
        $this->recipe->where('name', 'like', '%'.$query.'%');

        return $this;
    }

    public function filterByIngredientName(string $query): self
    {
        $this->recipe->orWhereRelation('ingredients', function ($dbQuery) use ($query) {
            return $dbQuery->where('ingredients.name', 'like', '%'.$query.'%');
        });

        return $this;
    }

    public function get(): ?Collection
    {
        return $this->recipe->get();
    }
}
