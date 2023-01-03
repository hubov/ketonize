<?php

namespace App\Services\Interfaces;

use App\Http\Controllers\UserDietController;
use App\Repositories\Interfaces\ProfileRepositoryInterface;

interface ProfileCreateOrUpdateInterface
{
    public function __construct(ProfileRepositoryInterface $profileRepository, UserDietController $userDietController);
    public function perform(int $userId, array $attributes) : array;
}
