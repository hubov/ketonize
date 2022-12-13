<?php

namespace App\Jobs;

use App\Models\DietPlan;
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
        foreach ($meals as $mealOrder => $meal) {
            $recipe = Recipe::join('recipe_tag', 'recipes.id', '=', 'recipe_id')
                    ->select('recipes.*')
                    ->where('tag_id', $meal['tag']->id)
                    ->whereBetween('protein_ratio', [$protein*0.5, $protein*1.5])
                    ->whereBetween('carbohydrate_ratio', [0, $carbohydrate*1.5])
                    ->whereNotIn('recipes.id', $chosenRecipes)
                    ->inRandomOrder()
                    ->first();

            $modifier = round($meal['kcal'] / $recipe->kcal * 100);

            DietPlan::create([
                'user_id' => $user->id,
                'modifier' => $modifier,
                'recipe_id' => $recipe->id,
                'meal' => $mealOrder,
                'date_on' => $this->date
            ]);

            $chosenRecipes[] = $recipe->id;
        }
    }
}
