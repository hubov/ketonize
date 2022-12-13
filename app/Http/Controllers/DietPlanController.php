<?php

namespace App\Http\Controllers;

use App\Jobs\GenerateDietPlan;
use App\Models\DietPlan;
use App\Models\Recipe;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class DietPlanController extends Controller
{
    public $date;
    public $dates;
    public $user;
    public $meals;
    protected $totalProtein;
    protected $totalFat;
    protected $totalCarbohydrate;
    protected $totalKcal;
    protected $totalPreparation;
    protected $totalTime;
    protected $shareProtein;
    protected $shareFat;
    protected $shareCarbohydrate;

    public function index(Request $request, $date = NULL)
    {
        $this->user = Auth::user();
        $this->setDate($date);
        $this->sumUp();
        $dietMealDivision = $this->user->userDiet->dietMealDivision();
        $mealsTags = (isset($dietMealDivision)) ? $dietMealDivision->mealsTags() : [];

        return View::make('dashboard', [
            'date' => $this->date,
            'datePrev' => $this->dates['prev'],
            'dateNext' => $this->dates['next'],
            'meals' => $this->meals,
            'units' => Unit::all(),
            'protein' => $this->totalProtein,
            'fat' => $this->totalFat,
            'carbohydrate' => $this->totalCarbohydrate,
            'kcal' => $this->totalKcal,
            'preparation_time' => $this->totalPreparation,
            'total_time' => $this->totalTime,
            'shareProtein' => $this->shareProtein,
            'shareFat' => $this->shareFat,
            'shareCarbohydrate' => $this->shareCarbohydrate,
            'diet' => $this->user->userDiet->diet->name,
            'dietKcal' => $this->user->userDiet->kcal,
            'dietProtein' => $this->user->userDiet->protein,
            'dietFat' => $this->user->userDiet->fat,
            'dietCarbohydrate' => $this->user->userDiet->carbohydrate,
            'dietProteinShare' => $this->user->userDiet->diet->protein,
            'dietFatShare' => $this->user->userDiet->diet->fat,
            'dietCarbohydrateShare' => $this->user->userDiet->diet->carbohydrate,
            'mealsTags' => $mealsTags
        ]);
    }

    public function setDate($date)
    {
        $this->date = ($date === NULL) ? date("Y-m-d") : $date;

        $this->dates();
    }

    public function dates()
    {
        $dateUnix = strtotime($this->date);
        $this->dates['next'] = date('Y-m-d', strtotime('+1 day', $dateUnix));
        $this->dates['prev'] = date('Y-m-d', strtotime('-1 day', $dateUnix));
    }

    public function getMeals()
    {
        $this->meals = (new DietPlan)->getCurrentMeal($this->date);
    }

    public function sumUp()
    {
        if ($this->meals === NULL) {
            $this->getMeals();
        }

        if (count($this->meals) > 0) {
            foreach ($this->meals as $meal) {
                $meal->scale();
                $meal->shares();

                $this->totalProtein += $meal->recipe->protein;
                $this->totalFat += $meal->recipe->fat;
                $this->totalCarbohydrate += $meal->recipe->carbohydrate;
                $this->totalKcal += $meal->recipe->kcal;
                $this->totalPreparation += $meal->recipe->preparation_time;
                $this->totalTime += $meal->recipe->total_time;
            }

            $macros = $this->totalProtein + $this->totalFat + $this->totalCarbohydrate;
            $this->shareProtein = round($this->totalProtein / $macros * 100);
            $this->shareFat = round($this->totalFat / $macros * 100);
            $this->shareCarbohydrate = round($this->totalCarbohydrate / $macros * 100);
        }

        return [
            'totalProtein' => $this->totalProtein,
            'totalFat' => $this->totalFat,
            'totalCarbohydrate' => $this->totalCarbohydrate,
            'totalKcal' => $this->totalKcal,
            'totalPreparation' => $this->totalPreparation,
            'totalTime' => $this->totalTime,
            'shareProtein' => $this->shareProtein,
            'shareFat' => $this->shareFat,
            'shareCarbohydrate' => $this->shareCarbohydrate
        ];
    }

    public function generate(Request $request, GenerateDietPlan $plan, $date)
    {
        $plan->setDate($date);
        $plan->handle(Auth::user());

        $url = ($date == date('Y-m-d')) ? '/dashboard' : '/dashboard/' . $date;

        return redirect($url);
    }

    public function update(Request $request)
    {
        $recipe = Recipe::where('slug', '=', $request->slug)->firstOrFail();

        $kcalSum = DietPlan::deleteCurrentMeal($request->date, $request->meal);
        $modifier = $kcalSum / $recipe->kcal * 100;

        $newMeal = DietPlan::create([
            'user_id' => Auth::user()->id,
            'modifier' => $modifier,
            'recipe_id' =>  $recipe->id,
            'meal' => $request->meal,
            'date_on' => $request->date
        ]);

        return response()->json($newMeal->id);
    }
}
