<?php

namespace App\Services\Interfaces;

use App\Models\DietPlan;
use App\Models\Recipe;
use App\Models\User;
use App\Repositories\Interfaces\DietPlanRepositoryInterface;

interface DietPlanInterface
{
    public function __construct(DietPlanRepositoryInterface $dietPlanRepository, MealInterface $mealService);
    public function setUser(User $user);
    public function getByDate(string|null $date) : DietPlan|null;
    public function getDates() : array;
    public function changeMeal(int $meal, string $newSlug): Recipe;
    public function createIfNotExists();
    public function updateAll();
    public function updateOnDate(string $date);
    public function delete() : bool;
    public function deleteOnDate(string $date) : bool;
    public function deleteAfterDate(string $date) : bool;
    public function isUpdatedAfter(string $dateTime) : bool;
}
