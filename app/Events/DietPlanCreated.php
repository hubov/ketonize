<?php

namespace App\Events;

use App\Models\DietPlan;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DietPlanCreated
{
    use Dispatchable, SerializesModels;

    public $dietPlan;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(DietPlan $dietPlan)
    {
        $this->dietPlan = $dietPlan;
    }
}
