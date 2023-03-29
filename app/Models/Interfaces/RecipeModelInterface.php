<?php

namespace App\Models\Interfaces;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

interface RecipeModelInterface
{
    public function ingredients(): BelongsToMany;
    public function customIngredients(): BelongsToMany;
}
