<?php

namespace App\Jobs;

use App\Models\DietPlan;
use App\Models\User;
use App\Models\Recipe;
use App\Models\Tag;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
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
    public function __construct($date = NULL)
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
        $mealsCount = 4;
        $meals[1] = [
            'tag' => 1,
            'kcal' => $user->userDiet->kcal * 0.3
        ];
        $meals[2] = [
            'tag' => 2,
            'kcal' => $user->userDiet->kcal * 0.3
        ];
        $meals[3] = [
            'tag' => 4,
            'kcal' => $user->userDiet->kcal * 0.1
        ];
        $meals[4] = [
            'tag' => 3,
            'kcal' => $user->userDiet->kcal * 0.3
        ];

        $macro = $user->userDiet->protein + $user->userDiet->fat + $user->userDiet->carbohydrate;
        $protein = $user->userDiet->protein / $macro * 100;
        $fat = $user->userDiet->fat / $macro * 100;
        $carbohydrate = $user->userDiet->carbohydrate / $macro * 100;

        $chosenRecipes = [];

        foreach ($meals as $key => $meal)
        {
            $recipe = Recipe::join('recipe_tag', 'recipes.id', '=', 'recipe_id')
                    ->select('recipes.*')
                    ->where('tag_id', $meal['tag'])
                    ->whereBetween('protein_ratio', [$protein*0.5, $protein*1.5])
                    // ->whereBetween('fat_ratio', [$fat*0.5, $fat*1.5])
                    ->whereBetween('carbohydrate_ratio', [0, $carbohydrate*1.5])
                    ->whereNotIn('recipes.id', $chosenRecipes)
                    ->inRandomOrder()
                    ->first();

            $modifier = round($meal['kcal'] / $recipe->kcal * 100);

            $dietPlan = new DietPlan;
            $dietPlan->user_id = $user->id;
            $dietPlan->modifier = $modifier;
            $dietPlan->recipe_id = $recipe->id;
            $dietPlan->meal = $key;
            $dietPlan->date_on = $this->date;
            $dietPlan->save();
            $chosenRecipes[] = $recipe->id;
        }
    }
}
