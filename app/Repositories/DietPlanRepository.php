<?php

namespace App\Repositories;

use App\Models\DietMealDivision;
use App\Models\DietPlan;
use App\Models\User;
use App\Repositories\Interfaces\DietPlanRepositoryInterface;

class DietPlanRepository implements DietPlanRepositoryInterface
{
    public function get(int $id) : DietPlan
    {
        return DietMealDivision::find($id);
    }

    public function getByDate(User $user, $date) : DietPlan
    {
        return Dietplan::where('user_id', $user->id)
                        ->where('date_on', $date)
                        ->firstOrNew();
    }
}
