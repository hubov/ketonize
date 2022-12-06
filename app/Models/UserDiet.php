<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDiet extends Model
{
    use HasFactory;

    protected $meals;
    protected $dietMealDivision;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function diet()
    {
        return $this->belongsTo(Diet::class);
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

    public function dietMealDivision()
    {
        $this->dietMealDivision = DietMealDivision::where('meals_count', $this->meals_count)->first();

        return $this->dietMealDivision;
    }

    public function getMeals()
    {
        if (!isset($this->dietMealDivision)) {
            $this->dietMealDivision();
        }
        $this->meals = $this->dietMealDivision->getMeals;

        return $this->meals;
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
        if  (!isset($this->meals)) {
            $this->getMeals();
        }

        $result = [];

        foreach ($this->meals as $meal) {
            $result[] = $meal->tag->id;
        }

        return $result;
    }
}
