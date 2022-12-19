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

    protected function getProteinAttribute()
    {
        return $this->scale($this->recipe->protein);
    }

    protected function getFatAttribute()
    {
        return $this->scale($this->recipe->fat);
    }

    protected function getCarbohydrateAttribute()
    {
        return $this->scale($this->recipe->carbohydrate);
    }

    protected function getKcalAttribute()
    {
        return $this->scale($this->recipe->kcal);
    }

    protected function scale($value)
    {
        return round($value * $this->modifier / 100);
    }

    protected function getMacrosAttribute()
    {
        return $this->recipe->protein + $this->recipe->fat + $this->recipe->carbohydrate;
    }

    protected function getShareProteinAttribute()
    {
        return $this->share($this->recipe->protein);
    }

    protected function getShareFatAttribute()
    {
        return $this->share($this->recipe->fat);
    }

    protected function getShareCarbohydrateAttribute()
    {
        return $this->share($this->recipe->carbohydrate);
    }

    protected function share($value)
    {
        return round($value / $this->macros * 100);
    }

    protected function getPreparationTimeAttribute()
    {
        return $this->recipe->preparation_time;
    }

    protected function getCookingTimeAttribute()
    {
        return $this->recipe->cooking_time;
    }

    protected function getTotalTimeAttribute()
    {
        return $this->recipe->total_time;
    }
}
