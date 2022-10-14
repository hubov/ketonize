<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'image', 'protein', 'fat', 'carbohydrate', 'kcal', 'description', 'preparation_time', 'cooking_time'];

    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class)->withPivot('amount');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function tagsIds()
    {
        $result = NULL;
        foreach ($this->tags as $tag) {
            $result[$tag->id] = true;
        }

        return $result;
    }
}
