<?php

namespace App\Services;

use App\Models\DietPlan;
use App\Models\Recipe;
use App\Repositories\Interfaces\RecipeRepositoryInterface;
use App\Services\Interfaces\AddMealsToDietPlanInterface;
use App\Services\Interfaces\MealInterface;

class AddMealsToDietPlanService implements AddMealsToDietPlanInterface
{
    protected $recipeRepository;
    protected $mealService;
    protected $dietPlan;
    protected $userDiet;
    protected $mealsDivision;
    protected $chosenRecipes = [];

    public function __construct(RecipeRepositoryInterface $recipeRepository, MealInterface $mealService)
    {
        $this->mealService = $mealService;
        $this->recipeRepository = $recipeRepository;
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
            $recipe = Recipe::join('recipe_tag', 'recipes.id', '=', 'recipe_id')
                ->select('recipes.*')
                ->whereNotIn('recipes.id', $this->chosenRecipes)
                ->where('tag_id', $meal['tag']->id)
                ->whereBetween('protein_ratio', [$this->userDiet->getProteinRatio() * 0.5, $this->userDiet->getProteinRatio() * 1.5])
                ->whereBetween('carbohydrate_ratio', [0, $this->userDiet->getCarbohydrateRatio() * 1.5])
                ->inRandomOrder()
                ->first();

            $this->mealService->add($recipe, $meal['kcal'], $mealOrder);

            $this->addChosenRecipe($recipe->id);
        }
    }

    protected function addChosenRecipe($recipeId)
    {
        $this->chosenRecipes[] = $recipeId;
    }
}
