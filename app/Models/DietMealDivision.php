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

    public function mealEnergyDivisions()
    {
        return $this->hasMany(MealEnergyDivision::class);
    }

    public function userDiets()
    {
        return $this->hasMany(UserDiet::class);
    }

    public function getMeals()
    {
        return $this->mealEnergyDivisions();
    }

    public function mealsTags()
    {
        $list = $this->getMeals;
        $result = [];

        if (isset($list)) {
            foreach ($list as $l) {
                $group[$l->meal_order][] = $l->tag_id;
            }
        }

        if (isset($group)) {
            foreach ($group as $order => $g) {
                $result[$order] = implode(",", $g);
            }
        }

        return $result;
    }
}
