<?php

namespace App\Services\Interfaces;

interface RecipeSearchInterface
{
    public function filters($filters = []);
    public function search();
}
