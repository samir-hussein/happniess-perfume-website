<?php

namespace App\Exceptions;

class GeneralException extends \Exception
{
    public function __construct($message)
    {
        parent::__construct($message);
    }

    public function render($request)
    {
        return redirect()->back()->with('error', $this->message);
    }
}
