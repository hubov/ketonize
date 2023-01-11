<?php

namespace App\Repositories;

use App\Models\DietPlan;
use App\Repositories\Interfaces\DietPlanRepositoryInterface;

class DietPlanRepository implements DietPlanRepositoryInterface
{
    public function get(int $id) : DietPlan
    {
        return DietPlan::find($id);
    }

    public function getByDate(int $userId, string $date) : DietPlan
    {
        return DietPlan::where('user_id', $userId)
                        ->where('date_on', $date)
                        ->firstOrNew();
    }
}
