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
    public function index(Request $request, $date = NULL)
    {
        $user = Auth::user();
        if ($date === NULL)
            $date = date("Y-m-d");
        $dateUnix = strtotime($date);
        $dateNext = date('Y-m-d', strtotime('+1 day', $dateUnix));
        $datePrev = date('Y-m-d', strtotime('-1 day', $dateUnix));

        $meals = DietPlan::where('user_id', $user->id)
                                ->where('date_on', $date)
                                ->orderBy('meal')
                                ->get();

        $totalProtein = 0;
        $totalFat = 0;
        $totalCarbohydrate = 0;
        $totalKcal = 0;
        $totalPreparation = 0;
        $totalTime = 0;
        $shareProtein = 0;
        $shareFat = 0;
        $shareCarbohydrate = 0;
        if (count($meals) > 0)
        {
            foreach ($meals as $meal)
            {
                $meal->recipe->protein = round($meal->recipe->protein * $meal->modifier / 100);
                $meal->recipe->fat = round($meal->recipe->fat * $meal->modifier / 100);
                $meal->recipe->carbohydrate = round($meal->recipe->carbohydrate * $meal->modifier / 100);
                $meal->recipe->kcal = round($meal->recipe->kcal * $meal->modifier / 100);
                $macros = $meal->recipe->protein + $meal->recipe->fat + $meal->recipe->carbohydrate;
                $meal->shareProtein = round($meal->recipe->protein / $macros * 100);
                $meal->shareFat = round($meal->recipe->fat / $macros * 100);
                $meal->shareCarbohydrate = round($meal->recipe->carbohydrate / $macros * 100);
                $totalProtein += $meal->recipe->protein;
                $totalFat += $meal->recipe->fat;
                $totalCarbohydrate += $meal->recipe->carbohydrate;
                $totalKcal += $meal->recipe->kcal;
                $totalPreparation += $meal->recipe->preparation_time;
                $totalTime += $meal->recipe->total_time;
            }

            $macros = $totalProtein + $totalFat + $totalCarbohydrate;
            $shareProtein = round($totalProtein / $macros * 100);
            $shareFat = round($totalFat / $macros * 100);
            $shareCarbohydrate = round($totalCarbohydrate / $macros * 100);
        }

        return View::make('dashboard', [
            'date' => $date,
            'datePrev' => $datePrev,
            'dateNext' => $dateNext,
            'meals' => $meals,
            'units' => Unit::all(),
            'protein' => $totalProtein,
            'fat' => $totalFat,
            'carbohydrate' => $totalCarbohydrate,
            'kcal' => $totalKcal,
            'preparation_time' => $totalPreparation,
            'total_time' => $totalTime,
            'shareProtein' => $shareProtein,
            'shareFat' => $shareFat,
            'shareCarbohydrate' => $shareCarbohydrate,
            'diet' => $user->userDiet->diet->name,
            'dietKcal' => $user->userDiet->kcal,
            'dietProtein' => $user->userDiet->protein,
            'dietFat' => $user->userDiet->fat,
            'dietCarbohydrate' => $user->userDiet->carbohydrate,
            'dietProteinShare' => $user->userDiet->diet->protein,
            'dietFatShare' => $user->userDiet->diet->fat,
            'dietCarbohydrateShare' => $user->userDiet->diet->carbohydrate
        ]); 
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
