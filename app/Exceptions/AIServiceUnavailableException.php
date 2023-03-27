<?php

namespace App\Exceptions;

class AIServiceUnavailableException extends UIThrowableException
{
    protected $status = 'warning';
    protected $symbol = 'report';
    protected $title = 'Service temporarily unavailable!';
    protected $message = 'Service is unavailable at the moment. We have been notified about the problem. Please try again later.';
}
