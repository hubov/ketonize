<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class DietPlan extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'date_on'];

    protected $totalProtein = 0;
    protected $totalFat = 0;
    protected $totalCarbohydrate = 0;
    protected $totalKcal = 0;
    protected $totalPreparation = 0;
    protected $totalTime = 0;
    protected $shareProtein = 0;
    protected $shareFat = 0;
    protected $shareCarbohydrate = 0;
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function meals()
    {
        return $this->hasMany(Meal::class)->orderBy('meal');
    }

    public function meal($meal)
    {
        return $this->meals()->where('meal', $meal)->get();
    }

    protected function getProteinAttribute()
    {
        return $this->sumUp('protein');
    }

    protected function getFatAttribute()
    {
        return $this->sumUp('fat');
    }

    protected function getCarbohydrateAttribute()
    {
        return $this->sumUp('carbohydrate');
    }

    protected function getKcalAttribute()
    {
        return $this->sumUp('kcal');
    }

    protected function getShareProteinAttribute()
    {
        return $this->share($this->protein);
    }

    protected function getShareFatAttribute()
    {
        return $this->share($this->fat);
    }

    protected function getShareCarbohydrateAttribute()
    {
        return $this->share($this->carbohydrate);
    }

    protected function getMacrosAttribute()
    {
        return $this->protein + $this->fat + $this->carbohydrate;
    }

    protected function getPreparationTimeAttribute()
    {
        return $this->sumUp('preparation_time');
    }

    protected function getCookingTimeAttribute()
    {
        return $this->sumUp('cooking_time');
    }

    protected function getTotalTimeAttribute()
    {
        return $this->sumUp('total_time');
    }

    protected function sumUp($attribute)
    {
        $result = 0;

        if (count($this->meals) > 0) {
            foreach ($this->meals as $meal) {
                $result += $meal->$attribute;
            }
        }

        return $result;
    }

    protected function share($value)
    {
        if ($this->macros == 0) {
            return 0;
        }

        return round($value / $this->macros * 100);
    }

//    public function getCurrentMeal($date, $meal = NULL)
//    {
//        $dietPlan = DietPlan::where('user_id', '=', Auth::user()->id)
//            ->where('date_on', '=', $date);
//
//        $dietPlan = ($meal != NULL) ? $dietPlan->where('meal', '=', $meal) : $dietPlan;
//
//        return $dietPlan->get();
//    }
//
//    public function deleteCurrentMeal($date, $meal)
//    {
//        $currentMeal = $this->getCurrentMeal($date, $meal);
//        $kcalSum = 0;
//
//        foreach ($currentMeal as $meal) {
//            $oldRecipe = Recipe::select('kcal')->where('id', '=', $meal->recipe_id)->first();
//            $kcalSum += $oldRecipe->kcal * $meal->modifier / 100;
//            $meal->delete();
//        }
//
//        return $kcalSum;
//    }

    public function addMeal($meal, $slug, $kcal)
    {
        $recipe = Recipe::where('slug', $slug)->firstOrFail();

        $modifier = $kcal / $recipe->kcal * 100;

        Meal::create([
            'diet_plan_id' => $this->id,
            'recipe_id' => $recipe->id,
            'meal' => $meal,
            'modifier' => $modifier
        ]);

        return $recipe;
    }

    public function changeMeal($meal, $newSlug)
    {
        $kcalSum = 0;
        foreach ($this->meal($meal) as $mealPart) {
            $kcalSum += $mealPart->kcal;
            $mealPart->delete();
        }

        $newMeal = $this->addMeal($meal, $newSlug, $kcalSum);

        return $newMeal;
    }
}
