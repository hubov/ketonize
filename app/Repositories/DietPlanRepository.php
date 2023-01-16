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

    public function getByDate(int $userId, string $date) : DietPlan|null
    {
        return DietPlan::where('user_id', $userId)
                        ->where('date_on', $date)
                        ->first();
    }

    public function createForUserBulk(int $userId, array $datesList): Collection
    {
        $user = $this->userRepository->get($userId);

        return $user->dietPlans()
            ->createMany(
                $this->arrangeBulk($userId, $datesList)
            );
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

    public function createForUserOnDate(int $userId, string $date): DietPlan
    {
        $user = $this->userRepository->get($userId);

        return $user->dietPlans()->create([
            'date_on' => $date
        ]);
    }

    public function deleteForUser(int $userId): bool
    {
        return DietPlan::where('user_id', $userId)
                        ->delete();
    }

    public function deleteForUserOnDate(int $userId, string $date): bool
    {
        return DietPlan::where('user_id', $userId)
                        ->where('date_on', $date)
                        ->delete();
    }

    public function deleteForUserAfterDate(int $userId, string $date): bool
    {
        return DietPlan::where('user_id', $userId)
                        ->where('date_on', '>=', $date)
                        ->delete();
    }

    public function isCompleteAfter(string $dateTime): bool
    {
        return DietPlan::where('date_on', substr($dateTime, 0, 10))
                            ->where('created_at', '>=', $dateTime)
                            ->exists();
    }
}
