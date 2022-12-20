<?php

namespace App\Repositories\Interfaces;

use App\Models\Recipe;

interface RecipeRepositoryInterface
{
    public function get(int $id) : Recipe;
    public function getBySlug(string $slug) : Recipe;
}
