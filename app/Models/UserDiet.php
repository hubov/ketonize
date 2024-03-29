<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDiet extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'diet_id', 'diet_meal_division_id', 'meals_count', 'kcal', 'protein', 'fat', 'carbohydrate'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function diet()
    {
        return $this->belongsTo(Diet::class);
    }

    public function dietMealDivision()
    {
        return $this->belongsTo(DietMealDivision::class);
    }

    protected function getMealsAttribute()
    {
        return $this->dietMealDivision->mealEnergyDivisions;
    }

    protected function getMealsCountAttribute()
    {
        return $this->dietMealDivision->meals_count;
    }

    public function getMacros()
    {
        return $this->protein + $this->fat + $this->carbohydrate;
    }

    public function getProteinRatio()
    {
        return $this->protein / $this->getMacros() * 100;
    }

    public function getCarbohydrateRatio()
    {
        return $this->carbohydrate / $this->getMacros() * 100;
    }

    public function mealsDivision()
    {
        $result = [];
        if (isset($this->meals)) {
            foreach ($this->meals as $meal) {
                $result[$meal->meal_order] = [
                    'tag' => $meal->tag,
                    'kcal' => $this->kcal * $meal->kcal_share / 100
                ];
            }
        }

        return $result;
    }

    public function getMealsTags()
    {
        $result = [];

        foreach ($this->meals as $meal) {
            $result[] = $meal->tag->id;
        }

        return $result;
    }
}
