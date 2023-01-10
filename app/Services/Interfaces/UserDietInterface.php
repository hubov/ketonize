<?php

namespace App\Services\Interfaces;

use App\Repositories\Interfaces\DietMealDivisionRepositoryInterface;
use App\Repositories\Interfaces\DietRepositoryInterface;
use App\Repositories\Interfaces\UserDietRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;

interface UserDietInterface
{
    public function __construct(
        UserDietRepositoryInterface $userDietRepository,
        DietRepositoryInterface $dietRepository,
        DietMealDivisionRepositoryInterface $dietMealDivRepository,
        UserRepositoryInterface $userRepository,
    );
    public function setUser(int $userId);
    public function setDiet(int $dietId);
    public function setMealsDivision(int $mealsCount);
    public function create();
    public function update();
}
