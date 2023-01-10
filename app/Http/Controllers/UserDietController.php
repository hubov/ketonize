<?php

namespace App\Http\Controllers;

use App\Jobs\GenerateDietPlan;
use App\Models\DietPlan;
use App\Services\UserDietService;
use Illuminate\Support\Facades\Auth;

class UserDietController extends Controller
{
    protected $userDietService;

    public function __construct(UserDietService $userDietService)
    {
        $this->userDietService = $userDietService;
    }

    public function create($dietId, $mealsCount) {
        $this->userDietService->setUser(Auth()->user()->id)
                                ->setDiet($dietId)
                                ->setMealsDivision($mealsCount)
                                ->create();

        $this->newDietPlan();
    }

    public function update($dietId, $mealsCount) {
        $this->userDietService->setUser(Auth()->user()->id)
                                ->setDiet($dietId)
                                ->setMealsDivision($mealsCount)
                                ->update();

        $this->newDietPlan();
    }

    protected function newDietPlan() {
        $dateStart = new \DateTime();
        $interval = new \DateInterval('P1D');
        $period = new \DatePeriod($dateStart, $interval, 28);
        foreach ($period as $date)
        {
            Dietplan::where('user_id', '=', Auth::user()->id)
                    ->where('date_on', '=', $date->format('Y-m-d'))
                    ->delete();

            $plan = new GenerateDietPlan($date->format('Y-m-d'));
            $user = Auth::user()->fresh();
            $plan->handle($user);
        }
    }
}
