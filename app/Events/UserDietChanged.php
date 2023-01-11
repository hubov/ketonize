<?php

namespace App\Events;

use App\Models\UserDiet;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserDietChanged
{
    use Dispatchable, SerializesModels;

    public $userDiet;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(UserDiet $userDiet)
    {
        $this->userDiet = $userDiet;
    }
}
