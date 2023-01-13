<?php

namespace App\Services;

use App\Http\Controllers\UserDietController;
use App\Repositories\Interfaces\ProfileRepositoryInterface;
use App\Services\Interfaces\ProfileCreateOrUpdateInterface;
use App\Services\Interfaces\ProfileInterface;
use App\Services\Interfaces\UserDietInterface;

class ProfileCreateOrUpdateService implements ProfileCreateOrUpdateInterface
{
    protected $profileRepository;
    protected $userDietService;
    protected $userDietController;

    public function __construct(ProfileRepositoryInterface $profileRepository, UserDietInterface $userDietService, UserDietController $userDietController)
    {
        $this->profileRepository = $profileRepository;
        $this->userDietService = $userDietService;
        $this->userDietController = $userDietController;

        return $this;
    }

    public function perform(int $userId, array $attributes) : array
    {
        $sortedAttributes = $this->sortAttributes($attributes);

        if ($this->profileRepository->ifExistsForUser($userId)) {
            return $this->update($userId, $sortedAttributes);
        } else {
            return $this->create($userId, $sortedAttributes);
        }
    }

    protected function sortAttributes($attributes)
    {
        return [
            'profile' => [
                'height' => $attributes['height'],
                'weight' => $attributes['weight'],
                'target_weight' => $attributes['target_weight'],
                'gender' => $attributes['gender'],
                'diet_target' => $attributes['diet_target'],
                'basic_activity' => $attributes['basic_activity'],
                'sport_activity' => $attributes['sport_activity'],
                'birthday' => $attributes['birthday']
            ],
            'user_diet' => [
                'diet_type' => $attributes['diet_type'],
                'meals_count' => $attributes['meals_count']
            ]
        ];
    }

    protected function create(int $userId, array $attributes) : array
    {
        $profile = $this->profileRepository->createForUser($userId, $attributes['profile']);

        $this->userDietService->setUser($userId)
            ->setDiet($attributes['user_diet']['diet_type'])
            ->setMealsDivision($attributes['user_diet']['meals_count'])
            ->create();

        return ['profile' => $profile];
    }

    protected function update(int $userId, array $attributes) : array
    {
        $profile = $this->profileRepository->updateForUser($userId, $attributes['profile']);

        $this->userDietService->setUser($userId)
            ->setDiet($attributes['user_diet']['diet_type'])
            ->setMealsDivision($attributes['user_diet']['meals_count'])
            ->update();

        return ['profile' => $profile];
    }
}
