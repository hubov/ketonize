<?php

namespace App\Repositories\Interfaces;

use App\Models\Ingredient;
use App\Models\Recipe;
use Illuminate\Support\Collection;

interface RecipeSearchRepositoryInterface
{
    public function __construct(
        Recipe $recipe,
        Ingredient $ingredient
    );
    public function filterByTags(array $tags) : self;
    public function filterByRecipeName(string $query) : self;
    public function filterByIngredientName(string $query) : self;
    public function get() : ?Collection;
}
