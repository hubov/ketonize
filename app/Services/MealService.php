<?php

namespace App\Services;

use App\Models\DietPlan;
use App\Models\Meal;
use App\Models\Recipe;
use App\Repositories\Interfaces\MealRepositoryInterface;
use App\Repositories\Interfaces\RecipeRepositoryInterface;
use App\Services\Interfaces\MealInterface;

class MealService implements MealInterface
{
    protected $mealRepository;
    protected $recipeRepository;
    protected $dietPlan;
    protected $userDiet;

    public function __construct(
        RecipeRepositoryInterface $recipeRepository,
        MealRepositoryInterface $mealRepository
    ) {
        $this->mealRepository = $mealRepository;
        $this->recipeRepository = $recipeRepository;
    }

    public function setDietPlan(DietPlan $dietPlan): self
    {
        $this->dietPlan = $dietPlan;

        return $this;
    }

    public function add(Recipe $recipe, int $kcal, int $mealOrder) : Meal
    {
        return $this->mealRepository->create([
            'diet_plan_id' => $this->dietPlan->id,
            'modifier' => $this->calculateModifier($kcal, $recipe->kcal),
            'recipe_id' => $recipe->id,
            'meal' => $mealOrder
        ]);
    }

    public function change($mealOrder, $recipeSlug) : Meal
    {
        $kcalSum = 0;

        foreach ($this->mealRepository->getByMeal($this->dietPlan->id, $mealOrder) as $mealPart) {
            $kcalSum += $mealPart->kcal;
            $this->delete($mealPart->id);
        }

        return $this->add(
            $this->recipeRepository->getBySlug($recipeSlug),
            $kcalSum,
            $mealOrder
        );
    }

    public function delete(int $mealId) : bool
    {
        return $this->mealRepository->delete($mealId);
    }

    public function calculateModifier(int $mealKcal, int $recipeKcal) : int
    {
        return round($mealKcal / $recipeKcal * 100);
    }

    public function getIngredientsBetweenDates(int $userId, string $dateFrom, string $dateTo): array
    {
        $meals = $this->mealRepository->getForUserBetweenDates($userId, $dateFrom, $dateTo);

        return $this->arrangeIngredients($meals);
    }

    protected function arrangeIngredients($meals)
    {
        $list = [];

        foreach ($meals as $meal) {
            foreach ($meal->ingredients as $ingredient) {
                if (isset($list[$ingredient->id])) {
                    $list[$ingredient->id]['amount'] += $ingredient->amount;
                } else {
                    $list[$ingredient->id] = [
                        'itemable_id' => $ingredient->id,
                        'itemable_type' => 'App\Models\Ingredient',
                        'amount' => $ingredient->amount
                    ];
                }
            }
        }

        return $list;
    }
}
