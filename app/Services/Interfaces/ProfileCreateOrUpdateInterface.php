<?php

namespace App\Services\Interfaces;

use App\Repositories\Interfaces\ProfileRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;

interface ProfileCreateOrUpdateInterface
{
    public function __construct(
        ProfileRepositoryInterface $profileRepository,
        UserDietInterface $userDietService,
        UserRepositoryInterface $userRepository
    );
    public function setUser(int $userId): self;
    public function create(): self;
    public function update(): self;
    public function perform(array $attributes) : array;
}
