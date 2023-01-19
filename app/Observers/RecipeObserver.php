<?php

namespace App\Observers;

use App\Models\Recipe;
use Illuminate\Support\Str;

class RecipeObserver
{
    public function saving(Recipe $recipe)
    {
        $recipe->image = ($recipe->image === NULL) ? Recipe::IMAGE_DEFAULT : $recipe->image;
        $recipe->slug = Str::of($recipe->name)->slug('-')->__toString();
        $recipe->name = Str::of($recipe->name)->ucfirst()->__toString();
        $recipe->total_time = $recipe->preparation_time + $recipe->cooking_time;
    }

    public function pivotSynced(Recipe $recipe, string $relationName)
    {
        if ($relationName == 'ingredients') {
            $recipe->resetMacros();

            if (count($recipe->ingredients) > 0) {
                foreach ($recipe->ingredients as $ingredientId => $ingredient) {
                    $recipe->addMacrosFromIngredient($ingredient, $ingredient->pivot->amount);
                }

                $recipe->updateMacroRatios();
                $recipe->save();
            }
        }
    }

    /**
     * Handle the Recipe "created" event.
     *
     * @param  \App\Models\Recipe  $recipe
     * @return void
     */
    public function created(Recipe $recipe)
    {
        //
    }

    /**
     * Handle the Recipe "updated" event.
     *
     * @param  \App\Models\Recipe  $recipe
     * @return void
     */
    public function updated(Recipe $recipe)
    {
        //
    }

    /**
     * Handle the Recipe "deleted" event.
     *
     * @param  \App\Models\Recipe  $recipe
     * @return void
     */
    public function deleted(Recipe $recipe)
    {
        //
    }

    /**
     * Handle the Recipe "restored" event.
     *
     * @param  \App\Models\Recipe  $recipe
     * @return void
     */
    public function restored(Recipe $recipe)
    {
        //
    }

    /**
     * Handle the Recipe "force deleted" event.
     *
     * @param  \App\Models\Recipe  $recipe
     * @return void
     */
    public function forceDeleted(Recipe $recipe)
    {
        //
    }
}
