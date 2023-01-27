<?php

namespace Tests\Unit\Services;

use App\Models\Profile;
use App\Models\User;
use App\Models\UserDiet;
use App\Repositories\Interfaces\ProfileRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Services\Interfaces\UserDietInterface;
use App\Services\ProfileCreateOrUpdateService;
use PHPUnit\Framework\TestCase;

class ProfileCreateOrUpdateServiceTest extends TestCase
{
    public $profileRepository;
    public $userDietService;
    public $userRepository;
    public $userId;
    public $user;
    public $profile;
    public $dietType;
    public $mealsCount;

    public function setUp(): void
    {
        parent::setUp();

        $this->userId = 1;
        $this->user = new User;
        $this->user->id = $this->userId;
        $this->profile = new Profile();
        $this->dietType = 2;
        $this->mealsCount = 3;

        $this->profileRepository = $this->createMock(ProfileRepositoryInterface::class);
        $this->userDietService = $this->createMock(UserDietInterface::class);
        $this->userRepository = $this->createMock(UserRepositoryInterface::class);

        $this->userRepository
            ->expects($this->once())
            ->method('get')
            ->with($this->userId)
            ->willReturn($this->user);

        $this->userDietService
            ->expects($this->atLeastOnce())
            ->method('setDiet')
            ->with($this->dietType)
            ->willReturnSelf();
        $this->userDietService
            ->expects($this->atLeastOnce())
            ->method('setMealsDivision')
            ->with($this->mealsCount)
            ->willReturnSelf();
    }

    /**
     * @test
     * @dataProvider provideCompleteData
     */
    public function creates_successfully_profile_with_complete_data($expectedResult, $data)
    {
        $this->arrangeFor('create');

        $profileCreateOrUpdateService = new ProfileCreateOrUpdateService($this->profileRepository, $this->userDietService, $this->userRepository);
        $result = $profileCreateOrUpdateService
            ->setUser($this->userId)
            ->create()
            ->perform($data);

        $this->assertIsObject($result['profile']);
        $this->assertEquals($this->profile, $result['profile']);
    }

    /**
     * @test
     * @dataProvider provideCompleteData
     */
    public function updates_successfully_profile_with_complete_data($expectedResult, $data)
    {
        $this->arrangeFor('update');

        $profileCreateOrUpdateService = new ProfileCreateOrUpdateService($this->profileRepository, $this->userDietService, $this->userRepository);
        $result = $profileCreateOrUpdateService
            ->setUser($this->userId)
            ->update()
            ->perform($data);

        $this->assertIsObject($result['profile']);
        $this->assertEquals($this->profile, $result['profile']);
    }

    /**
     * @test
     * @dataProvider provideCompleteData
     * @param $expectedResult
     * @param $data
     * @return void
     */
    public function creates_successfully_profile_with_complete_data_and_without_action_given($expectedResult, $data)
    {
        $this->arrangeFor('create');

        $profileCreateOrUpdateService = new ProfileCreateOrUpdateService($this->profileRepository, $this->userDietService, $this->userRepository);
        $result = $profileCreateOrUpdateService
            ->setUser($this->userId)
            ->perform($data);

        $this->assertIsObject($result['profile']);
        $this->assertEquals($this->profile, $result['profile']);
    }

    protected function arrangeFor(string $action): void
    {
        $this->profileRepository
            ->expects($this->once())
            ->method($action . 'ForUser')
            ->withAnyParameters()
            ->willReturn($this->profile);

        $this->userDietService
            ->expects($this->atLeastOnce())
            ->method('setProfile')
            ->with($this->profile)
            ->willReturnSelf();
        $this->userDietService
            ->expects($this->once())
            ->method($action)
            ->willReturn(new UserDiet());
    }

    public function provideCompleteData(): array
    {
        return [
            [
                'profile' => $this->profile,
                [
                    'height' => 170,
                    'weight' => 70,
                    'target_weight' => 60,
                    'gender' => 1,
                    'diet_target' => 1,
                    'basic_activity' => 1,
                    'sport_activity' => 1,
                    'birthday' => '2000-01-01',
                    'diet_type' => 2,
                    'meals_count' => 3,
                ],
            ],
        ];
    }
}
