<?php

namespace App\Exceptions;

class DietPlanUnderConstruction extends UIThrowableException
{
    protected $status = 'primary';
    protected $symbol = 'construction';
    protected $title = 'Meal plan under construction';
    protected $message = 'Your meal plan for the day is not ready yet. Our algorithms are already working on it! Give them a few more minutes, please 🙏';
}
