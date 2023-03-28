<?php

namespace App\Http\Traits;

use App\Models\Interfaces\IngredientModelInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

trait UniversalIngredientPicker
{
    public function getOrCreateIngredientByName($attributes): IngredientModelInterface
    {
        try {
            $ingredient = $this->ingredientRepository->getByName($attributes['name']);
        } catch (ModelNotFoundException) {
            $ingredient = $this->customIngredientRepository->getOrCreateForUserByName($this->userId, $attributes);
        }

        return $ingredient;
    }
}
