<?php

namespace App\Repositories\Interfaces;

use App\Models\Profile;

interface ProfileRepositoryInterface
{
    public function __construct(UserRepositoryInterface $userRepository);
    public function getForUser(int $userId) : Profile;
    public function createForUser(int $userId, array $attributes) : Profile;
    public function updateForUser(int $userId, array $attributes) : Profile;
    public function ifExistsForUser(int $userId) : bool;
}
