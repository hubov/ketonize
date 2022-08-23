<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'image', 'protein', 'fat', 'carbohydrate', 'kcal', 'description'];

    public function ingredients() {
        return $this->belongsToMany(Ingredient::class);
    }
}
