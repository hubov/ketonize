<?php

namespace App\Services;

use App\Events\DietPlanCreated;
use App\Http\Traits\DietPlanTimeline;
use App\Models\DietPlan;
use App\Models\Meal;
use App\Models\Recipe;
use App\Models\User;
use App\Repositories\Interfaces\DietPlanRepositoryInterface;
use App\Services\Interfaces\DietPlanInterface;
use App\Services\Interfaces\MealInterface;
use Illuminate\Support\Collection;

class DietPlanService implements DietPlanInterface
{
    use DietPlanTimeline;

    protected $dietPlanRepository;
    protected $mealService;
    protected $user;
    protected $date;
    protected $dietPlan;
    protected $meals;

    public function __construct(
        DietPlanRepositoryInterface $dietPlanRepository,
        MealInterface $mealService
    ) {
        $this->dietPlanRepository = $dietPlanRepository;
        $this->mealService = $mealService;

        return $this;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getByDate(string|null $date): ?DietPlan
    {
        $this->setDate($date);
        $this->dietPlan = $this->dietPlanRepository->getByDate($this->user->id, $this->date);

        return $this->dietPlan;
    }

    protected function setDate(string|null $date): void
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

    public function getMeals(): array
    {
        $this->dietPlan->load('meals');

        foreach ($this->dietPlan->meals as $meal) {
            $this->meals[$meal->meal][] = $meal;
        }
    }

    public function getMeal($mealOrder): Meal
    {
        if (!isset($this->meals)) {
            $this->getMeals();
        }

        return $this->meals[$mealOrder];
    }

    public function changeMeal(int $meal, string $newSlug): Recipe
    {
        return $this->mealService->setDietPlan($this->dietPlan)
                            ->change($meal, $newSlug)
                            ->recipe;
    }

    public function createIfNotExists(): ?DietPlan
    {
        if (!$this->getByDate($this->getLastSubscriptionDay())) {
            return $this->createOnDate($this->getLastSubscriptionDay());
        }

        return null;
    }

    protected function createAll()
    {
        $dietPlans = $this->dietPlanRepository->createForUserBulk($this->user->id, $this->getSubscriptionDatesArray());

        foreach ($dietPlans as $dietPlan) {
            event(new DietPlanCreated($dietPlan));
        }

        return $dietPlans;
    }

    protected function createOnDate(string $date)
    {
        $dietPlan = $this->dietPlanRepository->createForUserOnDate($this->user->id, $date);

        event(new DietPlanCreated($dietPlan));

        return $dietPlan;
    }

    public function updateAll(): Collection
    {
        $this->deleteAfterDate($this->getToday());
        return $this->createAll();
    }

    public function updateOnDate(string $date): DietPlan
    {
        $this->deleteOnDate($date);
        return $this->createOnDate($date);
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

    public function isUpdatedAfter(string $dateTime): bool
    {
        return $this->dietPlanRepository->isCompleteForUserAfter($this->user->id, $dateTime);
    }
}
