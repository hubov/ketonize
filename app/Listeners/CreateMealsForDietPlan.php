<?php

namespace App\Listeners;

use App\Events\DietPlanCreated;
use App\Services\Interfaces\AddMealsToDietPlanInterface;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateMealsForDietPlan implements ShouldQueue
{
    protected $addMealsToDietPlanService;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(AddMealsToDietPlanInterface $addMealsToDietPlanService)
    {
        $this->addMealsToDietPlanService = $addMealsToDietPlanService;
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\DietPlanCreated  $event
     * @return void
     */
    public function handle(DietPlanCreated $event)
    {
        $this->addMealsToDietPlanService->setDietPlan($event->dietPlan);
        $this->addMealsToDietPlanService->setUp();
    }
}
