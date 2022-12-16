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
    protected $dietPlan;

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

    public function index(Request $request, DietPlan $dietPlan, $date = NULL)
    {
        $this->dietPlan = $dietPlan;
        $this->user = Auth::user();
        $this->setDate($date);
        $this->dietPlan = $this->dietPlan
                                ->where('user_id', $this->user->id)
                                ->where('date_on', $this->date)
                                ->firstOrNew();

        $dietMealDivision = $this->user->userDiet->dietMealDivision();

        return View::make('dashboard', [
            'date' => $this->date,
            'datePrev' => $this->dates['prev'],
            'dateNext' => $this->dates['next'],
            'meals' => $this->dietPlan->meals,
            'units' => Unit::all(),
            'protein' => $this->dietPlan->protein,
            'fat' => $this->dietPlan->fat,
            'carbohydrate' => $this->dietPlan->carbohydrate,
            'kcal' => $this->dietPlan->kcal,
            'preparation_time' => $this->dietPlan->preparationTime,
            'total_time' => $this->dietPlan->totalTime,
            'shareProtein' => $this->dietPlan->shareProtein,
            'shareFat' => $this->dietPlan->shareFat,
            'shareCarbohydrate' => $this->dietPlan->shareCarbohydrate,
            'diet' => $this->user->userDiet->diet->name,
            'dietKcal' => $this->user->userDiet->kcal,
            'dietProtein' => $this->user->userDiet->protein,
            'dietFat' => $this->user->userDiet->fat,
            'dietCarbohydrate' => $this->user->userDiet->carbohydrate,
            'dietProteinShare' => $this->user->userDiet->diet->protein,
            'dietFatShare' => $this->user->userDiet->diet->fat,
            'dietCarbohydrateShare' => $this->user->userDiet->diet->carbohydrate,
            'mealsTags' => (isset($dietMealDivision)) ? $dietMealDivision->mealsTags() : []
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

        $kcalSum = (new DietPlan)->deleteCurrentMeal($request->date, $request->meal);
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
