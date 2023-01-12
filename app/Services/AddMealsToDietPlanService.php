<?php

namespace App\Services;

use App\Models\DietPlan;
use App\Services\Interfaces\AddMealsToDietPlanInterface;
use App\Services\Interfaces\MealInterface;
use App\Services\Interfaces\Recipe\SelectRecipeForDietInterface;

class AddMealsToDietPlanService implements AddMealsToDietPlanInterface
{
    protected $recipeRepository;
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
    }

    protected function getDietData()
    {
        $this->userDiet = $this->dietPlan->user->userDiet;
        $this->mealsDivision = $this->userDiet->mealsDivision();
    }

    public function setUp()
    {
        foreach ($this->mealsDivision as $mealOrder => $meal) {
            $recipe = $this->selectRecipeForDietService->setTags([$meal['tag']->id])
                                                ->setUserDiet($this->userDiet)
                                                ->ignoreRecipes($this->chosenRecipes)
                                                ->get();

            $this->mealService->add($recipe, $meal['kcal'], $mealOrder);

            $this->addChosenRecipe($recipe->id);
        }
    }

    protected function addChosenRecipe($recipeId)
    {
        $this->chosenRecipes[] = $recipeId;
    }
}
