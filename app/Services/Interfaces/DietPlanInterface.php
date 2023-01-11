<?php

namespace App\Services\Interfaces;

use App\Models\DietPlan;
use App\Models\User;
use App\Repositories\Interfaces\DietPlanRepositoryInterface;
use App\Repositories\Interfaces\MealRepositoryInterface;
use App\Repositories\Interfaces\RecipeRepositoryInterface;

interface DietPlanInterface
{
    public function __construct(DietPlanRepositoryInterface $dietPlanRepository, RecipeRepositoryInterface $recipeRepository, MealRepositoryInterface $mealRepository);
    public function setUser(User $user);
    public function getByDate($date) : DietPlan;
    public function getDates() : array;
    public function update();
    public function delete() : bool;
    public function deleteOnDate(string $date) : bool;
    public function deleteAfterDate(string $date) : bool;
}
