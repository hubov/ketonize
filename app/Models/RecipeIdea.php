<?php

namespace App\Models;

use App\Models\Interfaces\RecipeModelInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class RecipeIdea extends Model implements RecipeModelInterface
{
    use HasFactory;

    public function ingredients(): BelongsToMany
    {
        return $this->belongsToMany(Ingredient::class)->withPivot('amount');
    }

    public function customIngredients(): BelongsToMany
    {
        return $this->belongsToMany(CustomIngredient::class)->withPivot('amount');
    }
}
