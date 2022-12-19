<?php

namespace App\Jobs;

use App\Models\DietPlan;
use App\Models\Meal;
use App\Models\User;
use App\Models\Recipe;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateDietPlan implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->setDate();
    }

    public function setDate($date = NULL)
    {
        if  (is_null($date)) {
            $this->date = ((new \DateTime())->modify('+28 days'))->format('Y-m-d');
        } else {
            $this->date = $date;
        }
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(User $user = NULL)
    {
        if (is_null($user)) {
            foreach (User::all() as $user) {
                $this->task($user);
            }
        } else {
            $this->task($user);
        }
    }

    protected function task(User $user)
    {
        $protein = $user->userDiet->getProteinRatio();
        $carbohydrate = $user->userDiet->getCarbohydrateRatio();
        $chosenRecipes = [];

        $user->userDiet->getMeals();
        $meals = $user->userDiet->mealsDivision();

        $dietPlan = new DietPlan();
        $dietPlan->date_on = $this->date;
        $dietPlan->user()->associate($user);
        $dietPlan->save();

        foreach ($meals as $mealOrder => $meal) {

            // to do: transfer it to separate Service

            $recipe = Recipe::join('recipe_tag', 'recipes.id', '=', 'recipe_id')
                    ->select('recipes.*')
                    ->whereNotIn('recipes.id', $chosenRecipes)
                    ->where('tag_id', $meal['tag']->id)
                    ->whereBetween('protein_ratio', [$protein*0.5, $protein*1.5])
                    ->whereBetween('carbohydrate_ratio', [0, $carbohydrate*1.5])
                    ->inRandomOrder()
                    ->first();

            $modifier = round($meal['kcal'] / $recipe->kcal * 100);

            Meal::create([
                'diet_plan_id' => $dietPlan->id,
                'modifier' => $modifier,
                'recipe_id' => $recipe->id,
                'meal' => $mealOrder
            ]);

            $chosenRecipes[] = $recipe->id;
        }
    }
}
