<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShoppingList extends Model
{
    use HasFactory;

    public function ingredient() {
        return $this->belongsTo(Ingredient::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
