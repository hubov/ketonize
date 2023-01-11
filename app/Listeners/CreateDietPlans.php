<?php

namespace App\Listeners;

use App\Events\UserDietChanged;
use App\Services\Interfaces\DietPlanInterface;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateDietPlans implements ShouldQueue
{
    protected $dietPlanService;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(DietPlanInterface $dietPlanService)
    {
        $this->dietPlanService = $dietPlanService;
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\UserDietChanged  $event
     * @return void
     */
    public function handle(UserDietChanged $event)
    {
        $this->dietPlanService->setUser($event->userDiet->user);
        $this->dietPlanService->update();
    }
}
