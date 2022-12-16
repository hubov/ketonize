<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meal extends Model
{
    use HasFactory;

    protected $fillable = ['diet_plan_id', 'recipe_id', 'meal', 'modifier'];
    public $timestamps = false;

    public function dietPlan()
    {
        return $this->belongsTo(DietPlan::class);
    }

    public function recipe()
    {
        return $this->belongsTo(Recipe::class);
    }

    public function scale()
    {
        $this->recipe->protein = round($this->recipe->protein * $this->modifier / 100);
        $this->recipe->fat = round($this->recipe->fat * $this->modifier / 100);
        $this->recipe->carbohydrate = round($this->recipe->carbohydrate * $this->modifier / 100);
        $this->recipe->kcal = round($this->recipe->kcal * $this->modifier / 100);
    }

    public function shares()
    {
        $macros = $this->recipe->protein + $this->recipe->fat + $this->recipe->carbohydrate;
        $this->shareProtein = round($this->recipe->protein / $macros * 100);
        $this->shareFat = round($this->recipe->fat / $macros * 100);
        $this->shareCarbohydrate = round($this->recipe->carbohydrate / $macros * 100);
    }
}
