<?php

namespace App\Repositories;

use App\Models\DietPlan;
use App\Repositories\Interfaces\DietPlanRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Collection;

class DietPlanRepository implements DietPlanRepositoryInterface
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

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

    public function createForUserBulk(int $userId, array $datesList): Collection
    {
        $user = $this->userRepository->get($userId);

        $user->dietPlans()
            ->createMany(
                $this->arrangeBulk($userId, $datesList)
            );

        return $user->dietPlans;
    }

    protected function arrangeBulk(int $userId, $datesList)
    {
        $dietPlans = [];
        foreach ($datesList as $date) {
            $dietPlans[] = [
                'user_id' => $userId,
                'date_on' => $date
            ];
        }

        return $dietPlans;
    }

}
