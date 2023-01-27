<?php

namespace App\Exceptions;

use Exception;

abstract class UIThrowableException extends Exception
{
    protected $status = '';
    protected $symbol = '';
    protected $title = '';
    protected $message = '';

    public function getTitle()
    {
        return $this->title;
    }

    public function getSymbol()
    {
        return $this->symbol;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function returnErrorArray()
    {
        return [
            'status' => $this->getStatus(),
            'symbol' => $this->getSymbol(),
            'title' => $this->getTitle(),
            'message' => $this->getMessage()
        ];
    }
}
