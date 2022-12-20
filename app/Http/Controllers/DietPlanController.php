<?php

namespace App\Http\Controllers;

use App\Jobs\GenerateDietPlan;
use App\Models\DietPlan;
use App\Models\Unit;
use App\Repositories\Interfaces\DietPlanRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Services\DietPlanService;
use App\Services\Interfaces\DietPlanInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class DietPlanController extends Controller
{
    protected $dietPlanRepository;
    protected $dietPlanService;
    protected $userRepository;
    protected $planJob;

    public function __construct(DietPlanRepositoryInterface $dietPlanRepository, DietPlanInterface $dietPlanService, UserRepositoryInterface $userRepository, GenerateDietPlan $planJob)
    {
        $this->dietPlanRepository = $dietPlanRepository;
        $this->dietPlanService = $dietPlanService;
        $this->userRepository = $userRepository;
        $this->planJob = $planJob;
    }

    public function index(Request $request, $date = NULL)
    {
        $this->dietPlanService->setUser(Auth::user());
        $dietPlan = $this->dietPlanService->getByDate($date);

        $dietMealDivision = Auth::user()->userDiet->dietMealDivision;

        return View::make('dashboard', [
            'date' => $this->dietPlanService->getDates()['current'],
            'datePrev' => $this->dietPlanService->getDates()['prev'],
            'dateNext' => $this->dietPlanService->getDates()['next'],
            'meals' => $dietPlan->meals,
            'units' => Unit::all(),
            'protein' => $dietPlan->protein,
            'fat' => $dietPlan->fat,
            'carbohydrate' => $dietPlan->carbohydrate,
            'kcal' => $dietPlan->kcal,
            'preparation_time' => $dietPlan->preparationTime,
            'total_time' => $dietPlan->totalTime,
            'shareProtein' => $dietPlan->shareProtein,
            'shareFat' => $dietPlan->shareFat,
            'shareCarbohydrate' => $dietPlan->shareCarbohydrate,
            'diet' => Auth::user()->userDiet->diet->name,
            'dietKcal' => Auth::user()->userDiet->kcal,
            'dietProtein' => Auth::user()->userDiet->protein,
            'dietFat' => Auth::user()->userDiet->fat,
            'dietCarbohydrate' => Auth::user()->userDiet->carbohydrate,
            'dietProteinShare' => Auth::user()->userDiet->diet->protein,
            'dietFatShare' => Auth::user()->userDiet->diet->fat,
            'dietCarbohydrateShare' => Auth::user()->userDiet->diet->carbohydrate,
            'mealsTags' => (isset($dietMealDivision)) ? $dietMealDivision->mealsTags() : []
        ]);
    }

    public function generate(Request $request, $date)
    {
        $this->planJob->setDate($date);
        $this->planJob->handle(Auth::user());

        $url = ($date == date('Y-m-d')) ? '/dashboard' : '/dashboard/' . $date;

        return redirect($url);
    }

    public function update(Request $request)
    {
        $this->dietPlanService->setUser(Auth::user());
        $this->dietPlanService->getByDate($request->date);
//        $dietPlan = $this->dietPlanService->
//            ->where('user_id', Auth::user()->id)
//            ->where('date_on', $request->date)
//            ->firstOrFail();

        $newMeal = $this->dietPlanService->changeMeal($request->meal, $request->slug);

        return response()->json($newMeal->id);
    }
}
