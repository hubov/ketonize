<?php

namespace App\Models;

use App\Models\Interfaces\IngredientModelInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ingredient extends Model implements IngredientModelInterface
{
    use HasFactory;
    protected $fillable = ['name', 'ingredient_category_id', 'protein', 'fat', 'carbohydrate', 'kcal', 'unit_id'];

    public function unit() : BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function recipes()
    {
        return $this->belongsToMany(Recipe::class)->withPivot('amount');
    }

    public function ingredientCategory()
    {
        return $this->belongsTo(IngredientCategory::class);
    }

    public function category()
    {
        return $this->ingredientCategory();
    }

    public function nutrients()
    {
        return $this->belongsToMany(Nutrient::class)->withPivot('amount');
    }

    public function shoppingList()
    {
        return $this->morphOne(ShoppingList::class, 'itemable');
    }
}
