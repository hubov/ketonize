<?php

namespace App\Jobs;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Services\Interfaces\DietPlanInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GenerateDietPlan implements ShouldQueue
{
    use Dispatchable, Queueable, SerializesModels;

    protected $userRepository;
    protected $dietPlanService;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(UserRepositoryInterface $userRepository, DietPlanInterface $dietPlanService)
    {
        $this->userRepository = $userRepository;
        $this->dietPlanService = $dietPlanService;

        foreach ($this->userRepository->getAllActive() as $user) {
            $this->createDietPlan($user);
        }
    }

    protected function createDietPlan(User $user)
    {
        $this->dietPlanService->setUser($user)
            ->createIfNotExists();
    }
}
