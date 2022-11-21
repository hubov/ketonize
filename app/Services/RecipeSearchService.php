<?php

namespace App\Services;

use App\Models\Recipe;
use App\Services\Interfaces\RecipeSearchInterface;

class RecipeSearchService implements RecipeSearchInterface
{
    protected $filters;
    protected $recipes;

    public function filters($filters = [])
    {
        $this->filters = $filters;
    }

    public function search()
    {
        $this->recipes = Recipe::select('name', 'slug', 'image', 'preparation_time', 'total_time', 'protein_ratio', 'fat_ratio', 'carbohydrate_ratio');
        $this->applyFilters();

//        dd($this);

        return $this->recipes->get();
    }

    protected function applyFilters()
    {
        if (count($this->filters) > 0) {
            $filters = $this->filters;
            if (isset($filters['tags']) && (count($filters['tags']) > 0)) {
                $this->filterByTag($filters['tags']);
            }
            if (isset($filters['query']) && ($filters['query'] != '')) {
                $this->filterByQuery($filters['query']);
            }
        }
    }

    protected function filterByTag($filter) {
        $this->recipes->whereRelation('tags', function ($query) use ($filter) {
            return $query->whereIn('tags.id', $filter);
        });
    }

    protected function filterByQuery($query) {
        $this->filterByRecipeName($query);
        $this->filterByIngredientName($query);
    }

    protected function filterByRecipeName($query) {
        $this->recipes->where('name', 'like', '%'.$query.'%');
    }

    protected function filterByIngredientName($searchQuery) {
        $this->recipes->orWhereRelation('ingredients', function ($query) use ($searchQuery) {
            return $query->where('ingredients.name', 'like', '%'.$searchQuery.'%');
        });
    }
}
