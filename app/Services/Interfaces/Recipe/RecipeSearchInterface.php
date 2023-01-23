<?php

namespace App\Services\Interfaces\Recipe;

use Illuminate\Support\Collection;

interface RecipeSearchInterface
{
    public function filters($filters = []): self;
    public function search(): Collection;
}
