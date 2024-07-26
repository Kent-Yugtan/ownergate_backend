<?php

namespace App\Exceptions;

use Exception;

class UniqueConstraintViolationException extends Exception
{
    protected $message;
    protected $statusCode;

    public function __construct($message = "Duplicate entry detected", $statusCode = 409)
    {
        parent::__construct($message, $statusCode);
        $this->message = $message;
        $this->statusCode = $statusCode;
    }

    public function render($request)
    {
        return response()->json([
            'error' => $this->message,
        ], $this->statusCode);
    }
}
