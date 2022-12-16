<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class DietPlan extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'date_on'];

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scale()
    {
        $this->recipe->protein = round($this->recipe->protein * $this->modifier / 100);
        $this->recipe->fat = round($this->recipe->fat * $this->modifier / 100);
        $this->recipe->carbohydrate = round($this->recipe->carbohydrate * $this->modifier / 100);
        $this->recipe->kcal = round($this->recipe->kcal * $this->modifier / 100);
    }

    public function shares()
    {
        $macros = $this->recipe->protein + $this->recipe->fat + $this->recipe->carbohydrate;
        $this->shareProtein = round($this->recipe->protein / $macros * 100);
        $this->shareFat = round($this->recipe->fat / $macros * 100);
        $this->shareCarbohydrate = round($this->recipe->carbohydrate / $macros * 100);
    }

    public function getCurrentMeal($date, $meal = NULL)
    {
        $dietPlan = DietPlan::where('user_id', '=', Auth::user()->id)
            ->where('date_on', '=', $date);

        $dietPlan = ($meal != NULL) ? $dietPlan->where('meal', '=', $meal) : $dietPlan;

        return $dietPlan->get();
    }

    public function deleteCurrentMeal($date, $meal)
    {
        $currentMeal = $this->getCurrentMeal($date, $meal);
        $kcalSum = 0;

        foreach ($currentMeal as $meal) {
            $oldRecipe = Recipe::select('kcal')->where('id', '=', $meal->recipe_id)->first();
            $kcalSum += $oldRecipe->kcal * $meal->modifier / 100;
            $meal->delete();
        }

        return $kcalSum;
    }
}
