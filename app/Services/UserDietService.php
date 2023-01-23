<?php

namespace App\Services;

use App\Events\UserDietChanged;
use App\Models\Profile;
use App\Models\UserDiet;
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
    protected $user;
    protected $profile;
    protected $diet;
    protected $dietMealDivision;
    protected $kcalTotal;

    public function __construct(
        UserDietRepositoryInterface $userDietRepository,
        DietRepositoryInterface $dietRepository,
        DietMealDivisionRepositoryInterface $dietMealDivRepository,
    ) {
        $this->userDietRepository = $userDietRepository;
        $this->dietRepository = $dietRepository;
        $this->dietMealDivRepository = $dietMealDivRepository;
    }

    public function setDiet(int $dietId): self
    {
        $this->diet = $this->dietRepository->get($dietId);

        return $this;
    }

    public function setMealsDivision(int $mealsCount): self
    {
        $this->dietMealDivision = $this->dietMealDivRepository->getByMealsCount($mealsCount);

        return $this;
    }

    public function setProfile(Profile $profile): self
    {
        $this->profile = $profile;

        return $this;
    }

    public function create(): UserDiet
    {
        $userDiet = $this->userDietRepository->createForUser($this->profile->user_id, $this->setAttributes());

        event(new UserDietChanged($userDiet));

        return $userDiet;
    }

    public function update(): UserDiet
    {
        $userDiet = $this->userDietRepository->updateForUser($this->profile->user_id, $this->setAttributes());

        event(new UserDietChanged($userDiet));

        return $userDiet;
    }

    protected function setAttributes()
    {
        return [
            'diet_id' => $this->diet->id,
            'diet_meal_division_id' => $this->dietMealDivision->id,
            'kcal' => $this->calculateKcal(),
            'protein' => $this->calculateProtein(),
            'fat' => $this->calculateFat(),
            'carbohydrate' => $this->calculateCarbohydrate()
        ];
    }

    protected function calculateKcal() {
        $this->kcalTotal = $this->getBasicKcal() * $this->getBasicActivityModifier() * $this->getSportActivityModifier();
        $this->kcalTotal = round($this->kcalTotal * $this->getDietTarget() / 50) * 50;

        return $this->kcalTotal;
    }

    protected function getKcalGenderModifier()
    {
        switch ($this->profile->gender)
        {
            case 1: { return -161; }
            case 2: { return 5; }
        }
    }

    protected function getBasicKcal()
    {
        return round(
            9.99 * $this->profile->weight
            + 6.25 * $this->profile->height
            - 4.92 * $this->profile->age()
            + $this->getKcalGenderModifier()
        );
    }

    protected function getBasicActivityModifier()
    {
        switch ($this->profile->basic_activity)
        {
            case 1: { return 1.2; }
            case 2: { return 1.3; }
            case 3: { return 1.5; }
            case 4: { return 1.7; }
        }
    }

    protected function getSportActivityModifier()
    {
        switch ($this->profile->sport_activity)
        {
            case 1: { return 1; }
            case 2: { return 1.1; }
            case 3: { return 1.2; }
            case 4: { return 1.3; }
        }
    }

    protected function getDietTarget()
    {
        switch ($this->profile->diet_target)
        {
            case 1: { return 0.9; }
            case 2: { return 1; }
            case 3: { return 1.1; }
        }
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
