<?php

namespace App\Exceptions;

class DateOlderThanAccountException extends UIThrowableException
{
    protected $status = 'warning';
    protected $symbol = 'warning';
    protected $title = 'Date before account activation';
    protected $message = 'Unfortunately, your account was not yet active at that time.';
}
