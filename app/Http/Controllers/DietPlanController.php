<?php

namespace App\Http\Controllers;

use App\Exceptions\UIThrowableException;
use App\Jobs\GenerateDietPlan;
use App\Models\Unit;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\MealEnergyDivisionRepository;
use App\Services\Interfaces\DietPlanInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class DietPlanController extends Controller
{
    protected $dietPlanService;
    protected $userRepository;
    protected $planJob;
    protected $mealEnergyDivisionRepository;

    public function __construct(DietPlanInterface $dietPlanService, UserRepositoryInterface $userRepository, GenerateDietPlan $planJob, MealEnergyDivisionRepository $mealEnergyDivisionRepository)
    {
        $this->dietPlanService = $dietPlanService;
        $this->userRepository = $userRepository;
        $this->planJob = $planJob;
        $this->mealEnergyDivisionRepository = $mealEnergyDivisionRepository;
    }

    public function index(Request $request, $date = NULL)
    {
        $this->dietPlanService->setUser(Auth::user());
        try {
            $dietPlan = $this->dietPlanService->getByDate($date);
        } catch (UIThrowableException $e) {
            $errors[] = $e->returnErrorArray();
            $dietPlan = null;
        }

        $dietMealDivision = Auth::user()->userDiet->dietMealDivision;

        return View::make('dashboard', [
            'date' => $this->dietPlanService->getDates(),
            'units' => Unit::all(),
            'dietPlan' => $dietPlan,
            'userDiet' => Auth::user()->userDiet,
            'mealsTags' => (isset($dietMealDivision)) ? $dietMealDivision->mealsTags() : [],
            'mealsNames' => $this->mealEnergyDivisionRepository->getByDietMealDivision($dietMealDivision->id),
            'errors' => (isset($errors)) ? $errors : []
        ]);
    }

    public function generate(Request $request)
    {
        $this->dietPlanService->setUser(Auth::user());
        $this->dietPlanService->updateOnDate($request->date);

        $url = ($request->date == date('Y-m-d')) ? '/dashboard' : '/dashboard/' . $request->date;

        return redirect($url);
    }

    public function update(Request $request)
    {
        $this->dietPlanService->setUser(Auth::user());
        $this->dietPlanService->getByDate($request->date);

        $newMeal = $this->dietPlanService->changeMeal($request->meal, $request->slug);

        return response()->json($newMeal->id);
    }

    public function isReady(Request $request)
    {
        $profileUpdatedAt = new \DateTime();
        $profileUpdatedAt->setTimestamp($request->time);

        return response()->json(
            $this->dietPlanService
                ->setUser(Auth::user())
                ->isUpdatedAfter(
                    $profileUpdatedAt->format('Y-m-d H:i:s')
                )
        );
    }
}
