<?php

namespace Tests\Unit\Events;

use App\Events\UserDietChanged;
use App\Listeners\CreateDietPlans;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class UserDietChangedTest extends TestCase
{
    /** @test */
    public function event_and_listener_connected()
    {
        Event::fake();

        Event::assertListening(
            UserDietChanged::class,
            CreateDietPlans::class
        );
    }
}
