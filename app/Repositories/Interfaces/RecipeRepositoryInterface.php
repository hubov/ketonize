<?php

namespace App\Repositories\Interfaces;

use App\Models\Recipe;

interface RecipeRepositoryInterface
{
    public function get(int $id) : Recipe;
    public function getBySlug(string $slug) : Recipe;
    public function create(array $attributes) : Recipe;
    public function update(int $id, array $attributes) : Recipe;
    public function updateBySlug(string $slug, array $attributes) : Recipe;
    public function delete(int $id) : bool;
}
