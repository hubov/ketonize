<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'ingredient_category_id', 'protein', 'fat', 'carbohydrate', 'kcal', 'unit_id'];

    public function unit()
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
}
