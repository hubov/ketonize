<?php

namespace App\Repositories;

use App\Repositories\Interfaces\RecipeSearchRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class RecipeSearchRepository implements RecipeSearchRepositoryInterface
{
    protected $queryBuilder;
    protected $querySubqueries = [];

    public function __construct() {
        $this->queryBuilder = DB::table('recipes');
        $this->queryBuilder->select('name', 'slug', 'image', 'preparation_time', 'total_time', 'protein_ratio', 'fat_ratio', 'carbohydrate_ratio');
    }

    public function filterByTags(array $tags): self
    {
        $this->queryBuilder->whereIn('recipes.id', function ($query) use ($tags) {
            $query->select('recipe_id')
                ->from('recipe_tag')
                ->whereIn('tag_id', $tags);
        });

        return $this;
    }

    protected function filterByQuery()
    {
          $this->queryBuilder->whereIn(
              'recipes.id',
              $this->mergeSubqueries($this->querySubqueries)
          );
    }

    protected function mergesubQueries(array $subqueries)
    {
        return function ($queryBuilder) use ($subqueries) {
            $queryBuilder->selectRaw('0 as id');
            foreach ($subqueries as $key => $subquery) {
                $queryBuilder->union($subquery);
            }
        };
    }

    public function filterByRecipeName(string $query): self
    {
        $this->querySubqueries['recipe'] = function ($queryBuilder) use ($query) {
            $queryBuilder->select('id')
                ->from('recipes')
                ->where('name', 'like', '%' . $query . '%');
        };

        return $this;
    }

    public function filterByIngredientName(string $query): self
    {
        $this->querySubqueries['ingredient'] = function ($queryBuilder) use ($query) {
            $queryBuilder->select(DB::raw('ingredient_recipe.recipe_id as id'))
                ->from('ingredients')
                ->join('ingredient_recipe', 'ingredients.id', '=', 'ingredient_recipe.ingredient_id')
                ->where('ingredients.name', 'like', '%' . $query . '%');
        };

        return $this;
    }

    public function get(): ?Collection
    {
        if (count($this->querySubqueries)) {
            $this->filterByQuery();
        }

        return $this->queryBuilder->get();
    }
}
