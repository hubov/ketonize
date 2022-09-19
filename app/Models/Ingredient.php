<?php

namespace App\Models;

use App\Models\IngredientCategory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'category', 'protein', 'fat', 'carbohydrate', 'kcal', 'unit_id'];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function recipes()
    {
        return $this->belongsToMany(Recipe::class)->withPivot('amount');
    }

    public function category()
    {
        return $this->hasOne(IngredientCategory::class);
    }
}
