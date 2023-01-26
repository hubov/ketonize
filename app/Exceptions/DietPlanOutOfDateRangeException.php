<?php

namespace App\Exceptions;

class DietPlanOutOfDateRangeException extends UIThrowableException
{
    protected $status = 'warning';
    protected $symbol = 'warning';
    protected $title = 'Data range exceeded!';
    protected $message = 'You have exceeded the date range in which we store your meal plans. You can always access the plans for up to 2 weeks back and for 4 weeks into the future.';
}
