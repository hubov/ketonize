<?php

namespace App\Repositories;

use App\Models\Profile;
use App\Repositories\Interfaces\ProfileRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;

class ProfileRepository implements ProfileRepositoryInterface
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getForUser(int $userId) : Profile
    {
        return Profile::where('user_id', $userId)->first();
    }

    public function createForUser(int $userId, array $attributes): Profile
    {
        $attributes['user_id'] = $userId;

        return Profile::create($attributes);
    }

    public function updateForUser(int $userId, array $attributes): Profile
    {
        Profile::where('user_id', $userId)->update($attributes);

        return Profile::where('user_id', $userId)->first();
    }

    public function ifExistsForUser(int $userId): bool
    {
        return Profile::where('user_id', $userId)->exists();
    }
}
