<?php

namespace App\Repositories\Interfaces;

use App\Models\UserDiet;

interface UserDietRepositoryInterface
{
    public function getByUser(int $userId) : UserDiet|null;
    public function createForUser(int $userId, array $attributes) : UserDiet;
    public function updateForUser(int $userId, array $attributes) : UserDiet;
    public function deleteForUser(int $userId) : bool;
}
