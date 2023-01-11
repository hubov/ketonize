<?php

namespace App\Services;

use App\Events\DietPlanCreated;
use App\Http\Traits\DietPlanTimeline;
use App\Models\DietPlan;
use App\Models\User;
use App\Repositories\Interfaces\DietPlanRepositoryInterface;
use App\Repositories\Interfaces\MealRepositoryInterface;
use App\Repositories\Interfaces\RecipeRepositoryInterface;
use App\Services\Interfaces\DietPlanInterface;

class DietPlanService implements DietPlanInterface
{
    use DietPlanTimeline;

    protected $dietPlanRepository;
    protected $recipeRepository;
    protected $mealRepository;
    protected $user;
    protected $date;
    protected $dietPlan;
    protected $meals;

    public function __construct(DietPlanRepositoryInterface $dietPlanRepository, RecipeRepositoryInterface $recipeRepository, MealRepositoryInterface $mealRepository)
    {
        $this->dietPlanRepository = $dietPlanRepository;
        $this->recipeRepository = $recipeRepository;
        $this->mealRepository = $mealRepository;

        return $this;
    }

    public function setUser(User $user)
    {
        $this->user = $user;

        return $this;
    }

    public function getByDate($date): DietPlan
    {
        $this->setDate($date);
        $this->dietPlan = $this->dietPlanRepository->getByDate($this->user->id, $this->date);

        return $this->dietPlan;
    }

    protected function setDate($date): void
    {
        $this->date = ($date === NULL) ? date("Y-m-d") : $date;
    }

    public function getDates(): array
    {
        $dates['current'] = $this->date;
        $dateUnix = strtotime($this->date);
        $dates['next'] = date('Y-m-d', strtotime('+1 day', $dateUnix));
        $dates['prev'] = date('Y-m-d', strtotime('-1 day', $dateUnix));

        return $dates;
    }

    public function getMeals()
    {
        $this->dietPlan->load('meals');

        foreach ($this->dietPlan->meals as $meal) {
            $this->meals[$meal->meal][] = $meal;
        }
    }

    public function getMeal($meal)
    {
        if (!isset($this->meals)) {
            $this->getMeals();
        }

        return $this->meals[$meal];
    }

    public function addMeal($meal, $slug, $kcal)
    {
        $recipe = $this->recipeRepository->getBySlug($slug);

        $modifier = $kcal / $recipe->kcal * 100;

        $this->mealRepository->create([
            'diet_plan_id' => $this->dietPlan->id,
            'recipe_id' => $recipe->id,
            'meal' => $meal,
            'modifier' => $modifier
        ]);

        return $recipe;
    }

    public function changeMeal($meal, $newSlug)
    {
        $kcalSum = 0;

        foreach ($this->getMeal($meal) as $mealPart) {
            $kcalSum += $mealPart->kcal;
            $this->mealRepository->delete($mealPart->id);
        }

        $newMeal = $this->addMeal($meal, $newSlug, $kcalSum);

        return $newMeal;
    }

    public function update()
    {
        $this->deleteAfterDate($this->getToday());
        $this->create();
    }
    public function delete(): bool
    {
        return $this->dietPlanRepository->deleteForUser($this->user->id);
    }

    public function deleteOnDate(string $date): bool
    {
        return $this->dietPlanRepository->deleteForUserOnDate($this->user->id, $date);
    }

    public function deleteAfterDate(string $date): bool
    {
        return $this->dietPlanRepository->deleteForUserAfterDate($this->user->id, $date);
    }

    protected function create()
    {
        $dietPlans =  $this->dietPlanRepository->createForUserBulk($this->user->id, $this->getSubscriptionDatesArray());

        foreach ($dietPlans as $dietPlan) {
            event(new DietPlanCreated($dietPlan));
        }

        return $dietPlans;
    }
}
