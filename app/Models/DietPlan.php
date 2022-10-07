<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DietPlan extends Model
{
    use HasFactory;

    public function recipe() {
        return $this->belongsTo(Recipe::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function scale() {
        $this->recipe->protein = round($this->recipe->protein * $this->modifier / 100);
        $this->recipe->fat = round($this->recipe->fat * $this->modifier / 100);
        $this->recipe->carbohydrate = round($this->recipe->carbohydrate * $this->modifier / 100);
        $this->recipe->kcal = round($this->recipe->kcal * $this->modifier / 100);
    }

    public function shares() {
        $macros = $this->recipe->protein + $this->recipe->fat + $this->recipe->carbohydrate;
        $this->shareProtein = round($this->recipe->protein / $macros * 100);
        $this->shareFat = round($this->recipe->fat / $macros * 100);
        $this->shareCarbohydrate = round($this->recipe->carbohydrate / $macros * 100);
    }
}
