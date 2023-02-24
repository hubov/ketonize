<?php

namespace App\Models;

use App\Models\Interfaces\IngredientModelInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomIngredient extends Model implements IngredientModelInterface
{
    use HasFactory;

    public function unit() : BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }
}
