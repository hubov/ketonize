<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'protein', 'fat', 'carbohydrate', 'kcal', 'unit_id'];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}
