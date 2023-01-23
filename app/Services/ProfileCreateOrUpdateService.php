<?php

namespace App\Services;

use App\Repositories\Interfaces\ProfileRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Services\Interfaces\ProfileCreateOrUpdateInterface;
use App\Services\Interfaces\UserDietInterface;

class ProfileCreateOrUpdateService implements ProfileCreateOrUpdateInterface
{
    protected $profileRepository;
    protected $userDietService;
    protected $userRepository;
    protected $action = NULL;

    public function __construct(ProfileRepositoryInterface $profileRepository, UserDietInterface $userDietService, UserRepositoryInterface $userRepository)
    {
        $this->profileRepository = $profileRepository;
        $this->userDietService = $userDietService;
        $this->userRepository = $userRepository;

        return $this;
    }

    public function setUser(int $userId)
    {
        $this->user = $this->userRepository->get($userId);

        return $this;
    }

    public function create()
    {
        $this->action = 'create';

        return $this;
    }

    public function update()
    {
        $this->action = 'update';

        return $this;
    }

    protected function isActionUpdate()
    {
        switch ($this->action) {
            case 'create': { return false; }
            case 'update': { return true; }
            default: { return $this->profileRepository->ifExistsForUser($this->user->id); }
        }
    }

    public function perform(array $attributes) : array
    {
        $sortedAttributes = $this->sortAttributes($attributes);

        if ($this->isActionUpdate()) {
            return $this->performUpdate($sortedAttributes);
        } else {
            return $this->performCreate($sortedAttributes);
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

    protected function performCreate(array $attributes) : array
    {
        $profile = $this->profileRepository->createForUser($this->user->id, $attributes['profile']);

        $this->userDietService
            ->setDiet($attributes['user_diet']['diet_type'])
            ->setMealsDivision($attributes['user_diet']['meals_count'])
            ->setProfile($profile)
            ->create();

        return ['profile' => $profile];
    }

    protected function performUpdate(array $attributes) : array
    {
        $profile = $this->profileRepository->updateForUser($this->user->id, $attributes['profile']);

        $this->userDietService
            ->setDiet($attributes['user_diet']['diet_type'])
            ->setMealsDivision($attributes['user_diet']['meals_count'])
            ->setProfile($profile)
            ->update();

        return ['profile' => $profile];
    }
}
