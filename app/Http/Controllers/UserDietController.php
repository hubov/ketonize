<?php

namespace App\Http\Controllers;

use DateTime;
use App\Models\Diet;
use App\Models\Profile;
use App\Models\UserDiet;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class UserDietController extends Controller
{
    public function create(Profile $profile, $dietId) {
        $kcalTotal = $this->kcal($profile);

        $diet = Diet::find($dietId);
        $userDiet = new UserDiet;
        $userDiet->user_id = Auth::user()->id;
        $userDiet->diet_id = $diet->id;
        $userDiet->kcal = $kcalTotal;
        $userDiet->protein = round(($kcalTotal * $diet->protein / 100) / 4);
        $userDiet->fat = round(($kcalTotal * $diet->fat / 100) / 9);
        $userDiet->carbohydrate = round(($kcalTotal * $diet->carbohydrate / 100) / 4);
        $userDiet->save();
    }

    public function update(Profile $profile, $dietId) {
        $kcalTotal = $this->kcal($profile);

        $diet = Diet::find($dietId);
        $userDiet = UserDiet::where('user_id', Auth::user()->id)->first();
        $userDiet->diet_id = $diet->id;
        $userDiet->kcal = $kcalTotal;
        $userDiet->protein = round(($kcalTotal * $diet->protein / 100) / 4);
        $userDiet->fat = round(($kcalTotal * $diet->fat / 100) / 9);
        $userDiet->carbohydrate = round(($kcalTotal * $diet->carbohydrate / 100) / 4);
        $userDiet->save();
    }

    protected function kcal(Profile $profile) {
        switch ($profile->gender)
        {
            case 1: { $genderModifier = -161; break; }
            case 2: { $genderModifier = 5; break; }
        }

        $birthday = new DateTime($profile->birthday);
        $today = new DateTime(date("Y-m-d"));

        $age = $today->diff($birthday)->y;

        $kcalBasic = round(9.99 * $profile->weight + 6.25 * $profile->height - 4.92 * $age + $genderModifier);

        switch ($profile->basic_activity)
        {
            case 1: { $basicActivityModifier = 1.2; break; }
            case 2: { $basicActivityModifier = 1.3; break; }
            case 3: { $basicActivityModifier = 1.5; break; }
            case 4: { $basicActivityModifier = 1.7; break; }
        }

        switch ($profile->sport_activity)
        {
            case 1: { $sportActivityModifier = 1; break; }
            case 2: { $sportActivityModifier = 1.1; break; }
            case 3: { $sportActivityModifier = 1.2; break; }
            case 4: { $sportActivityModifier = 1.3; break; }
        }

        $kcalTotal = $kcalBasic * $basicActivityModifier * $sportActivityModifier;

        switch ($profile->diet_target)
        {
            case 1: { $kcalTotal *= 0.9; break; }
            case 3: { $kcalTotal *= 1.1; break; }
        }

        return round($kcalTotal / 50) * 50;
    }
}
