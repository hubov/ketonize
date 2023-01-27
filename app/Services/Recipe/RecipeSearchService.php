<?php

namespace App\Services\Recipe;

use App\Models\Recipe;
use App\Repositories\Interfaces\RecipeSearchRepositoryInterface;
use App\Services\Interfaces\Recipe\RecipeSearchInterface;
use Illuminate\Support\Collection;

class RecipeSearchService implements RecipeSearchInterface
{
    protected $recipeSearchRepository;
    protected $filters;

    public function __construct(RecipeSearchRepositoryInterface $recipeSearchRepository)
    {
        $this->recipeSearchRepository = $recipeSearchRepository;

        return $this;
    }

    public function filters($filters = []): self
    {
        $this->filters = $filters;

        return $this;
    }

    public function search(): ?Collection
    {
        $this->applyFilters();

        return $this->recipeSearchRepository->get();
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
        $this->recipeSearchRepository->filterByTags($filter);
    }

    protected function filterByQuery($query) {
        $this->filterByRecipeName($query);
        $this->filterByIngredientName($query);
    }

    protected function filterByRecipeName($query) {
        $this->recipeSearchRepository->filterByRecipeName($query);
    }

    protected function filterByIngredientName($query) {
        $this->recipeSearchRepository->filterByIngredientName($query);
    }
}
