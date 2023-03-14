<?php

namespace App\Models;

use App\Models\Interfaces\IngredientModelInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomIngredient extends Model implements IngredientModelInterface
{
    use HasFactory;

    protected $fillable = ['name', 'user_id', 'unit_id'];

    public function unit() : BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function getIngredientCategoryIdAttribute()
    {
        return 1000;
    }

    public function shoppingList()
    {
        return $this->morphOne(ShoppingList::class, 'itemable');
    }
}
