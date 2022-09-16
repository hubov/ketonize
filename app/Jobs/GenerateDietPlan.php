<?php

namespace App\Jobs;

use App\Models\DietPlan;
use App\Models\User;
use App\Models\Recipe;
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
    public function __construct($date)
    {
        $this->date = $date;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(User $user = NULL)
    {
        if ($user === NULL)
        {
            foreach (User::all() as $user)
            {
                $this->task($user);
            }
        }
        else
        {
            $this->task($user);
        }
    }

    protected function task(User $user)
    {
        $mealsCount = 4;
        $meals[1] = $user->userDiet->kcal * 0.3;
        $meals[2] = $user->userDiet->kcal * 0.3;
        $meals[3] = $user->userDiet->kcal * 0.1;
        $meals[4] = $user->userDiet->kcal * 0.3;

        $macro = $user->userDiet->protein + $user->userDiet->fat + $user->userDiet->carbohydrate;
        $protein = $user->userDiet->protein / $macro * 100;
        $fat = $user->userDiet->fat / $macro * 100;
        $carbohydrate = $user->userDiet->carbohydrate / $macro * 100;

        foreach ($meals as $key => $meal)
        {
            $recipe = Recipe::whereBetween('protein_ratio', [$protein*0.5, $protein*1.5])
                    ->whereBetween('fat_ratio', [$fat*0.5, $fat*1.5])
                    ->whereBetween('carbohydrate_ratio', [$carbohydrate*0.5, $carbohydrate*1.5])
                    ->inRandomOrder()
                    ->get();

            dd($recipe);

            $modifier = round($meal / $recipe->kcal * 100);

            $dietPlan = new DietPlan;
            $dietPlan->user_id = $user->id;
            $dietPlan->modifier = $modifier;
            $dietPlan->recipe_id = $recipe->id;
            $dietPlan->meal = $key;
            $dietPlan->date_on = $date;
            $dietPlan->save();
        }
    }
}
