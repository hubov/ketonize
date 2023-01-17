<?php

namespace App\Services;

use App\Models\DietPlan;
use App\Services\Interfaces\AddMealsToDietPlanInterface;
use App\Services\Interfaces\MealInterface;
use App\Services\Interfaces\Recipe\SelectRecipeForDietInterface;

class AddMealsToDietPlanService implements AddMealsToDietPlanInterface
{
    protected $mealService;
    protected $selectRecipeForDietService;
    protected $dietPlan;
    protected $userDiet;
    protected $mealsDivision;
    protected $chosenRecipes = [];

    public function __construct(MealInterface $mealService, SelectRecipeForDietInterface $selectRecipeForDietService)
    {
        $this->mealService = $mealService;
        $this->selectRecipeForDietService = $selectRecipeForDietService;
    }

    public function setDietPlan(DietPlan $dietPlan)
    {
        $this->dietPlan = $dietPlan;

        $this->mealService->setDietPlan($this->dietPlan);

        $this->getDietData();

        return $this;
    }

    protected function getDietData()
    {
        $this->userDiet = $this->dietPlan->user->userDiet;
        $this->mealsDivision = $this->userDiet->mealsDivision();
    }

    public function setUp()
    {
        foreach ($this->mealsDivision as $mealOrder => $meal) {
            $recipe = $this->getRecipe($meal['tag']->id);

            $this->mealService->add($recipe, $meal['kcal'], $mealOrder);

            $this->addChosenRecipe($recipe->id);
        }
    }

    protected function getRecipe($tagId)
    {
        return $this->selectRecipeForDietService->setTags([$tagId])
            ->setUserDiet($this->userDiet)
            ->ignoreRecipes($this->chosenRecipes)
            ->get();
    }

    public function addChosenRecipe($recipeId)
    {
        $this->chosenRecipes[] = $recipeId;
    }
}
