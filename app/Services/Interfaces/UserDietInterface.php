<?php

namespace App\Services\Interfaces;

use App\Models\Profile;
use App\Models\UserDiet;
use App\Repositories\Interfaces\DietMealDivisionRepositoryInterface;
use App\Repositories\Interfaces\DietRepositoryInterface;
use App\Repositories\Interfaces\UserDietRepositoryInterface;

interface UserDietInterface
{
    public function __construct(
        UserDietRepositoryInterface $userDietRepository,
        DietRepositoryInterface $dietRepository,
        DietMealDivisionRepositoryInterface $dietMealDivRepository,
    );
    public function setDiet(int $dietId): self;
    public function setMealsDivision(int $mealsCount): self;
    public function setProfile(Profile $profile): self;
    public function create(): UserDiet;
    public function update(): UserDiet;
}
