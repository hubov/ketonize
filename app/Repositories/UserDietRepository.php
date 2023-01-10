<?php

namespace App\Repositories;

use App\Models\UserDiet;
use App\Repositories\Interfaces\UserDietRepositoryInterface;

class UserDietRepository implements UserDietRepositoryInterface
{

    public function getByUser(int $userId): UserDiet|null
    {
        return UserDiet::where('user_id', $userId)->first();
    }

    public function createForUser(int $userId, array $attributes): UserDiet
    {
        $attributes['user_id'] = $userId;

        return UserDiet::create($attributes);
    }

    public function updateForUser(int $userId, array $attributes): UserDiet
    {
        $this->getByUser($userId)->update($attributes);

        return $this->getByUser($userId);
    }

    public function deleteForUser(int $userId): bool
    {
        return $this->getByUser($userId)
                    ->delete();
    }
}
