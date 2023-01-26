<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DietPlan extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'date_on'];
    protected $with = ['meals'];
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function meals()
    {
        return $this->hasMany(Meal::class)->orderBy('meal');
    }

    protected function getProteinAttribute()
    {
        return $this->sumUp('protein');
    }

    protected function getFatAttribute()
    {
        return $this->sumUp('fat');
    }

    protected function getCarbohydrateAttribute()
    {
        return $this->sumUp('carbohydrate');
    }

    protected function getKcalAttribute()
    {
        return $this->sumUp('kcal');
    }

    protected function getShareProteinAttribute()
    {
        return $this->share($this->protein);
    }

    protected function getShareFatAttribute()
    {
        return $this->share($this->fat);
    }

    protected function getShareCarbohydrateAttribute()
    {
        return $this->share($this->carbohydrate);
    }

    protected function getMacrosAttribute()
    {
        return $this->protein + $this->fat + $this->carbohydrate;
    }

    protected function getPreparationTimeAttribute()
    {
        return $this->sumUp('preparation_time');
    }

    protected function getCookingTimeAttribute()
    {
        return $this->sumUp('cooking_time');
    }

    protected function getTotalTimeAttribute()
    {
        return $this->sumUp('total_time');
    }

    protected function sumUp($attribute)
    {
        $result = 0;

        if (count($this->meals) > 0) {
            foreach ($this->meals as $meal) {
                $result += $meal->$attribute;
            }
        }

        return $result;
    }

    protected function share($value)
    {
        if ($this->macros == 0) {
            return 0;
        }

        return round($value / $this->macros * 100);
    }
}
