<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DietMealDivision extends Model
{
    use HasFactory;

    public function tag()
    {
        return $this->belongsTo(Tag::class);
    }

    public function mealsTags($mealsCount)
    {
        $list = DietMealDivision::where('meals_count', '=', $mealsCount)->get();

        foreach ($list as $l) {
            $group[$l->meal_order][] = $l->tag_id;
        }

        foreach ($group as $order => $g) {
            $result[$order] = implode(",", $g);
        }

        return $result;
    }
}
