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
                foreach ($recipe->ingredients as $ingredient) {
                    $recipe->addMacrosFromIngredient($ingredient, $ingredient->pivot->amount);
                }

                $recipe->updateMacroRatios();
                $recipe->save();
            }
        }
    }
}
