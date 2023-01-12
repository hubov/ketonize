<?php

namespace App\Services\Interfaces\Recipe;

interface RecipeSearchInterface
{
    public function filters($filters = []);
    public function search();
}
