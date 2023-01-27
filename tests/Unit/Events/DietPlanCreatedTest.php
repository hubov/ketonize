<?php

namespace Tests\Unit\Events;

use App\Events\DietPlanCreated;
use App\Listeners\CreateMealsForDietPlan;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class DietPlanCreatedTest extends TestCase
{
    /** @test */
    public function event_and_listener_connected()
    {
        Event::fake();

        Event::assertListening(
            DietPlanCreated::class,
            CreateMealsForDietPlan::class
        );
    }
}
