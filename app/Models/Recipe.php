<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Recipe extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'image', 'protein', 'fat', 'carbohydrate', 'kcal', 'description', 'preparation_time', 'cooking_time'];

    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class)->withPivot('amount');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    protected static function boot()
    {
        $autoValues = function($recipe) {
            $recipe->slug = Str::of($recipe->name)->slug('-')->__toString();
            $recipe->name = Str::of($recipe->name)->ucfirst()->__toString();
            $recipe->total_time = $recipe->preparation_time + $recipe->cooking_time;
        };

        parent::boot();
        static::saving($autoValues);
        static::creating($autoValues);
        static::updating($autoValues);
    }

    public function tagsIds()
    {
        $result = NULL;
        foreach ($this->tags as $tag) {
            $result[$tag->id] = true;
        }

        return $result;
    }

    public function resetMacros()
    {
        $this->protein = 0;
        $this->fat = 0;
        $this->carbohydrate = 0;
        $this->kcal = 0;
        $this->protein_ratio = 0;
        $this->fat_ratio = 0;
        $this->carbohydrate_ratio = 0;
    }

    public function removeIngredients()
    {
        $this->ingredients()->detach();
        $this::resetMacros();
    }

    public function setIngredients($ingredientIds, $amounts)
    {
        $this->removeIngredients();

        if ($ingredientIds > 0) {
            foreach ($ingredientIds as $i => $id) {
                $this->ingredients()->attach($id, ['amount' => $amounts[$i]]);
                $ingredient = Ingredient::find($id);
                $this->addMacrosFromIngredient($ingredient, $amounts[$i]);
            }

            $this->updateMacroRatios();
        }
    }

    protected function addProtein($amount)
    {
        $this->protein += $amount;
    }

    protected function addFat($amount)
    {
        $this->fat += $amount;
    }

    protected function addCarbohydrate($amount)
    {
        $this->carbohydrate += $amount;
    }

    protected function addKcal($amount)
    {
        $this->kcal += $amount;
    }

    public function addMacrosFromIngredient($ingredient, $amount)
    {
        $this->addProtein($amount * $ingredient->protein / 100);
        $this->addFat($amount * $ingredient->fat / 100);
        $this->addCarbohydrate($amount * $ingredient->carbohydrate / 100);
        $this->addKcal($amount * $ingredient->kcal / 100);
    }

    protected function setProteinRatio($ratio)
    {
        $this->protein_ratio = round($ratio);
    }

    protected function setFatRatio($ratio)
    {
        $this->fat_ratio = round($ratio);
    }

    protected function setCarbohydrateRatio($ratio)
    {
        $this->carbohydrate_ratio = round($ratio);
    }

    public function updateMacroRatios()
    {
        $macros = $this->protein + $this->fat + $this->carbohydrate;
        $this->setProteinRatio($this->protein / $macros * 100);
        $this->setFatRatio($this->fat / $macros * 100);
        $this->setCarbohydrateRatio($this->carbohydrate / $macros * 100);
    }

    public function equals(self $other): bool
    {
        if (
            $this->name === $other->name &&
            $this->slug === $other->slug &&
            $this->image === $other->image &&
            $this->description === $other->description &&
            $this->preparation_time === $other->preparation_time &&
            $this->cooking_time === $other->cooking_time &&
            $this->ingredients->modelKeys() === $other->ingredients->modelKeys() &&
            $this->tags->modelKeys() === $other->tags->modelKeys()
        ) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
}
