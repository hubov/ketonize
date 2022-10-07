<?php

namespace App\Http\Controllers;

use App\Jobs\GenerateDietPlan;
use App\Models\DietPlan;
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
            'dietCarbohydrateShare' => $this->user->userDiet->diet->carbohydrate
        ]);
    }

    public function setDate($date)
    {
        if ($date === NULL)
            $this->date = date("Y-m-d");
        else
            $this->date = $date;

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
        $this->meals = DietPlan::where('user_id', $this->user->id)
            ->where('date_on', $this->date)
            ->orderBy('meal')
            ->get();
    }

    public function sumUp()
    {
        if ($this->meals === NULL)
            $this->getMeals();

        if (count($this->meals) > 0)
        {
            foreach ($this->meals as $key => $meal)
            {
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
    }

    public function generate(Request $request, $date)
    {
        $plan = new GenerateDietPlan($date);
        $plan->handle(Auth::user());

        if ($date == date('Y-m-d'))
            $url = '/dashboard';
        else
            $url = '/dashboard/'.$date;

        return redirect($url);
    }
}
