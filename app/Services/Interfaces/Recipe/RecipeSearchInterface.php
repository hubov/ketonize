<?php

namespace App\Services\Interfaces\Recipe;

use App\Repositories\Interfaces\RecipeSearchRepositoryInterface;
use Illuminate\Support\Collection;

interface RecipeSearchInterface
{
    public function __construct(RecipeSearchRepositoryInterface $recipeSearchRepository);
    public function filters($filters = []): self;
    public function search(): ?Collection;
}
