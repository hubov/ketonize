<?php

namespace App\Services\Interfaces;

use App\Models\DietPlan;
use App\Models\Meal;
use App\Models\Recipe;
use App\Models\User;
use App\Repositories\Interfaces\DietPlanRepositoryInterface;
use Illuminate\Support\Collection;

interface DietPlanInterface
{
    public function __construct(DietPlanRepositoryInterface $dietPlanRepository, MealInterface $mealService);
    public function setUser(User $user): self;
    public function getByDate(string|null $date) : ?DietPlan;
    public function getDates() : array;
    public function getMeals(): array;
    public function getMeal($mealOrder): Meal;
    public function changeMeal(int $meal, string $newSlug): Recipe;
    public function createIfNotExists(): ?DietPlan;
    public function updateAll(): Collection;
    public function updateOnDate(string $date): DietPlan;
    public function delete() : bool;
    public function deleteOnDate(string $date) : bool;
    public function deleteAfterDate(string $date) : bool;
    public function isUpdatedAfter(string $dateTime) : bool;
}
