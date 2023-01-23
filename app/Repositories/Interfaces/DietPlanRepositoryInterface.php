<?php

namespace App\Repositories\Interfaces;

use App\Models\DietPlan;
use Illuminate\Support\Collection;

interface DietPlanRepositoryInterface
{
    public function __construct(UserRepositoryInterface $userRepository);
    public function get(int $id) : DietPlan;
    public function getByDate(int $userId, string $date) : ?DietPlan;
    public function createForUserBulk(int $userId, array $datesList) : Collection;
    public function createForUserOnDate(int $userId, string $date) : DietPlan;
    public function deleteForUser(int $userId) : bool;
    public function deleteForUserOnDate(int $userId, string $date) : bool;
    public function deleteForUserAfterDate(int $userId, string $date) : bool;
    public function isCompleteForUserAfter(int $userId, string $dateTime) : bool;
}
