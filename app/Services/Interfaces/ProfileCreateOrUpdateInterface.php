<?php

namespace App\Services\Interfaces;

use App\Repositories\Interfaces\ProfileRepositoryInterface;

interface ProfileCreateOrUpdateInterface
{
    public function perform(int $userId, array $attributes) : array;
    public function __construct(ProfileRepositoryInterface $profileRepository, UserDietInterface $userDietService, UserRepositoryInterface $userRepository);
}
