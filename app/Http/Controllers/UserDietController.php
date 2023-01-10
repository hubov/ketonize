<?php

namespace App\Http\Controllers;

use App\Jobs\GenerateDietPlan;
use App\Models\DietPlan;
use App\Models\Profile;
use App\Models\UserDiet;
use App\Repositories\Interfaces\DietMealDivisionRepositoryInterface;
use App\Repositories\Interfaces\DietRepositoryInterface;
use App\Services\UserDietService;
use Illuminate\Support\Facades\Auth;

class UserDietController extends Controller
{
    protected $userDietService;
    protected $profile;
    protected $dietRepository;
    protected $dietMealDiv;

    public function __construct(UserDietService $userDietService, DietRepositoryInterface $dietRepository, DietMealDivisionRepositoryInterface $dietMealDivRepository, Profile $profile)
    {
        $this->userDietService = $userDietService;
        $this->dietRepository = $dietRepository;
        $this->dietMealDivRepository = $dietMealDivRepository;
        $this->profile = $profile;
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

    protected function kcal() {
        switch ($this->profile->gender)
        {
            case 1: { $genderModifier = -161; break; }
            case 2: { $genderModifier = 5; break; }
        }

        $kcalBasic = round(9.99 * $this->profile->weight + 6.25 * $this->profile->height - 4.92 * $this->profile->age() + $genderModifier);

        switch ($this->profile->basic_activity)
        {
            case 1: { $basicActivityModifier = 1.2; break; }
            case 2: { $basicActivityModifier = 1.3; break; }
            case 3: { $basicActivityModifier = 1.5; break; }
            case 4: { $basicActivityModifier = 1.7; break; }
        }

        switch ($this->profile->sport_activity)
        {
            case 1: { $sportActivityModifier = 1; break; }
            case 2: { $sportActivityModifier = 1.1; break; }
            case 3: { $sportActivityModifier = 1.2; break; }
            case 4: { $sportActivityModifier = 1.3; break; }
        }

        $kcalTotal = $kcalBasic * $basicActivityModifier * $sportActivityModifier;

        switch ($this->profile->diet_target)
        {
            case 1: { $kcalTotal *= 0.9; break; }
            case 3: { $kcalTotal *= 1.1; break; }
        }

        return round($kcalTotal / 50) * 50;
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
