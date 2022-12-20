<?php

namespace App\Repositories\Interfaces;

use App\Models\DietPlan;
use App\Models\User;

interface DietPlanRepositoryInterface
{
    public function get(int $id) : DietPlan;
    public function getByDate(User $user, $date) : DietPlan;
}