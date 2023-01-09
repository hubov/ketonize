<?php

namespace App\Services;

use App\Repositories\Interfaces\DietMealDivisionRepositoryInterface;
use App\Repositories\Interfaces\DietRepositoryInterface;
use App\Repositories\Interfaces\UserDietRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Services\Interfaces\UserDietInterface;

class UserDietService implements UserDietInterface
{
    protected $userDietRepository;
    protected $dietRepository;
    protected $dietMealDivRepository;
    protected $userRepository;
    protected $user;
    protected $diet;
    protected $kcalTotal;

    public function __construct(
        UserDietRepositoryInterface $userDietRepository,
        DietRepositoryInterface $dietRepository,
        DietMealDivisionRepositoryInterface $dietMealDivRepository,
        UserRepositoryInterface $userRepository
    ) {
        $this->userDietRepository = $userDietRepository;
        $this->dietRepository = $dietRepository;
        $this->dietMealDivRepository = $dietMealDivRepository;
        $this->userRepository = $userRepository;
    }

    public function setUser(int $userId)
    {
        $this->user = $this->userRepository->get($userId);

        return $this;
    }

    public function create($dietId, $mealsCount)
    {
        if ($this->diet = $this->dietRepository->get($dietId)) {
            $attributes = [
                'user_id' => $this->user->id,
                'diet_id' => $this->diet->id,
                'diet_meal_division_id' => $this->dietMealDivRepository->getByMealsCount($mealsCount)->id,
                'kcal' => $this->calculateKcal(),
                'protein' => $this->calculateProtein(),
                'fat' => $this->calculateFat(),
                'carbohydrate' => $this->calculateCarbohydrate()
            ];

            $this->userDietRepository->createForUser($this->user->id, $attributes);
        }
    }

    protected function calculateKcal() {
        switch ($this->user->profile->gender)
        {
            case 1: { $genderModifier = -161; break; }
            case 2: { $genderModifier = 5; break; }
        }

        $kcalBasic = round(9.99 * $this->user->profile->weight + 6.25 * $this->user->profile->height - 4.92 * $this->user->profile->age() + $genderModifier);

        switch ($this->user->profile->basic_activity)
        {
            case 1: { $basicActivityModifier = 1.2; break; }
            case 2: { $basicActivityModifier = 1.3; break; }
            case 3: { $basicActivityModifier = 1.5; break; }
            case 4: { $basicActivityModifier = 1.7; break; }
        }

        switch ($this->user->profile->sport_activity)
        {
            case 1: { $sportActivityModifier = 1; break; }
            case 2: { $sportActivityModifier = 1.1; break; }
            case 3: { $sportActivityModifier = 1.2; break; }
            case 4: { $sportActivityModifier = 1.3; break; }
        }

        $kcalTotal = $kcalBasic * $basicActivityModifier * $sportActivityModifier;

        switch ($this->user->profile->diet_target)
        {
            case 1: { $kcalTotal *= 0.9; break; }
            case 3: { $kcalTotal *= 1.1; break; }
        }

        $this->kcalTotal = round($kcalTotal / 50) * 50;

        return $this->kcalTotal;
    }

    protected function calculateProtein()
    {
        return round($this->scaleMacroByKcal($this->diet->protein) / 4);
    }

    protected function calculateFat()
    {
        return round($this->scaleMacroByKcal($this->diet->fat) / 9);
    }

    protected function calculateCarbohydrate()
    {
        return round($this->scaleMacroByKcal($this->diet->carbohydrate) / 4);
    }

    protected function scaleMacroByKcal($macro)
    {
        return ($this->kcalTotal * $macro / 100);
    }
}
