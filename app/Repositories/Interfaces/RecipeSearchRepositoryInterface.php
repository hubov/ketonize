<?php

namespace App\Repositories\Interfaces;

use Illuminate\Support\Collection;

interface RecipeSearchRepositoryInterface
{
    public function __construct();
    public function filterByTags(array $tags) : self;
    public function filterByRecipeName(string $query) : self;
    public function filterByIngredientName(string $query) : self;
    public function get() : ?Collection;
}
