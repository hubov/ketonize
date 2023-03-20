<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShoppingList extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['user_id', 'itemable_id', 'itemable_type', 'amount'];

    public function itemable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
